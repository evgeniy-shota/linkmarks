<?php

namespace App\Services;

use App\Actions\GetLastOrderInContext;
use App\Actions\SetUrlForBookmarksThumbnail;
use App\Enums\ThumbnailSource;
use App\Http\Filters\FilterByTags;
use App\Http\Requests\Bookmark\StoreBookmarkRequest;
use App\Jobs\ProcessThumbnail;
use App\Models\Bookmark;
use App\Models\Context;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as ECollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookmarkService
{
    public function __construct(
        protected ThumbnailService $thumbnailService,
        protected StorageService $storageService
    ) {}

    public function search(string $searchRequest, string $userId)
    {
        $bookmarks = Bookmark::search($searchRequest)->query(
            function ($builder) {
                $builder->with('tags:id,name,description');
            }
        )->where('user_id', $userId)->get();

        $thumbnailsId = [];

        foreach ($bookmarks as $bookmark) {
            $thumbnailsId[] = $bookmark->thumbnail_id;
        }

        $thumbnails = $this->thumbnailService
            ->getThumbnailsIn($thumbnailsId, ['id', 'name'])->keyBy('id');

        $bookmarks->map(function ($bookmark) use ($thumbnails) {
            $bookmark->thumbnail = $this->thumbnailService
                ->generateUrl($thumbnails[$bookmark->thumbnail_id]);
        });

        // dump();
        return $bookmarks;
    }

    public function getAllBookmarks(int $userId): ?EloquentBuilder
    {
        $bookmarks = Bookmark::with('tags:id,name,description')->select(
            'bookmarks.id',
            'bookmarks.context_id',
            'bookmarks.link',
            'bookmarks.name',
            'bookmarks.thumbnail_id',
            'bookmarks.order',
            'thumbnails.name as thumbnail',
        )->leftJoin('thumbnails', 'bookmarks.thumbnail_id', '=', 'thumbnails.id')
            ->where('bookmarks.user_id', $userId);

        return $bookmarks;
    }

    public function getFilteredBookmarks(
        int $id,
        int $userId,
        bool $contextualFiltration,
        bool $discardToBookmarks,
        array $filterParams
    ) {
        if (!$contextualFiltration && count($filterParams) > 0) {

            $bookmarks = $this->getAllBookmarks($userId);

            if (!$discardToBookmarks) {
                $bookmarkFilter = app()->make(
                    FilterByTags::class,
                    [
                        'queryParams' => $filterParams,
                        'tableName' => 'bookmarks_tags'
                    ],

                );
                $bookmarks = $bookmarks->filter($bookmarkFilter);
            }

            $bookmarks = $bookmarks->get();
        } else {
            $bookmarks = $this->bookmarksFromContext($id);

            if (count($filterParams) > 0) {
                if (!$discardToBookmarks) {
                    $bookmarkFilter = app()->make(
                        FilterByTags::class,
                        [
                            'queryParams' => $filterParams,
                            'tableName' => 'bookmarks_tags'
                        ],
                    );
                    $bookmarks = $bookmarks->filter($bookmarkFilter);
                }
            }

            $bookmarks = $bookmarks->get();
        }

        $bookmarks =
            SetUrlForBookmarksThumbnail::pathToUrl($bookmarks);
        return $bookmarks;
    }

    public function bookmark(int $id, bool $withTags = true): ?Bookmark
    {
        $bookmark = Bookmark::query();

        if ($withTags) {
            $bookmark->with('tags:id,name,description');
        }

        return $bookmark->where('id', $id)->with('thumbnail:id,name')->first();
    }

    public function bookmarksIn(array $ids): ?ECollection
    {
        $bookmarks = Bookmark::whereIn('id', $ids)->with('thumbnail')->get();
        return $bookmarks;
    }

    public function bookmarksFromContext(int $idContext): Builder
    {
        $bookmarks = Bookmark::with('tags:id,name,description')->select(
            'bookmarks.id',
            'bookmarks.context_id',
            'bookmarks.link',
            'bookmarks.name',
            'bookmarks.thumbnail_id',
            'bookmarks.order',
            'thumbnails.name as thumbnail',
        )->leftJoin('thumbnails', 'bookmarks.thumbnail_id', '=', 'thumbnails.id')
            ->where('bookmarks.context_id', $idContext)->orderBy('order');

        return $bookmarks;
    }

    public function createBookmark(array $data, int $userId)
    {
        if (isset($data['thumbnailFile'])) {
            $parsedLink = parse_url($data['link']);
            $thumbnailFile = $this->storageService
                ->save($data['thumbnailFile']);
            $thumbnail = $this->thumbnailService->create(
                $thumbnailFile,
                Auth::id(),
                ThumbnailSource::UserLoad->value,
                $parsedLink['host'] ?? '',
            );
            $data['thumbnail_id'] = $thumbnail->id;

            if (ImageService::fileCanProcessed($thumbnailFile)) {
                ProcessThumbnail::dispatch($thumbnail)
                    ->delay(now()->addMinutes(1));
            }

            unset($data['thumbnailFile']);
        } else if (!isset($data['thumbnail_id'])) {
            $data['thumbnail_id'] = $this->thumbnailService
                ->getDefault()->id;
        }

        if (!isset($data['order'])) {
            $data['order'] =
                GetLastOrderInContext::getOrder($data['context_id']);
        }

        $data['order'] += 1;
        $data['user_id'] = $userId;
        $bookmark = Bookmark::create($data);
        $bookmark->thumbnail = Storage::url(
            $bookmark->thumbnail->name ??
                ($this->thumbnailService->getDefault())->name
        );

        if (isset($data['tags'])) {
            $bookmark->tags()->attach($data['tags']);
        }

        return $bookmark;
    }

    public function updateBookmark(
        int $id,
        array $data
    ): ?Bookmark {

        if (isset($data['thumbnailFile'])) {
            $parsedLink = parse_url($data['link']);
            $thumbnailFile = $this->storageService
                ->save($data['thumbnailFile']);
            $thumbnail = $this->thumbnailService->create(
                $thumbnailFile,
                Auth::id(),
                ThumbnailSource::UserLoad->value,
                $parsedLink['host'] ?? '',
            );
            $data['thumbnail_id'] = $thumbnail->id;

            if (ImageService::fileCanProcessed($thumbnailFile)) {
                ProcessThumbnail::dispatch($thumbnail)
                    ->delay(now()->addMinutes(1));
            }

            unset($data['thumbnailFile']);
        } else if (!isset($data['thumbnail_id'])) {
            $data['thumbnail_id'] = $this->thumbnailService
                ->getDefault()->id;
        }

        $tags = null;

        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        $bookmark = Bookmark::find($id);

        if (
            !isset($data['order'])
            || $bookmark->context_id != $data['context_id']
        ) {
            $maxOrder = GetLastOrderInContext::getOrder($data['context_id']);
            $data['order'] = $maxOrder + 1;
        }

        $bookmark->update($data);
        $bookmark->tags()->detach();

        if (isset($tags)) {
            $bookmark->tags()->attach($tags);
        }

        $bookmark->thumbnail = Storage::url(
            $bookmark->thumbnail->name ??
                ($this->thumbnailService->getDefault()->name)
        );
        return $bookmark;
    }

    public function deleteBookmark(int $id): ?bool
    {
        $result = Bookmark::destroy($id);
        return $result === 0 ? false : true;
    }
}
