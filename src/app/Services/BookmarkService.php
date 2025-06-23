<?php

namespace App\Services;

use App\Actions\GetLastOrderInContext;
use App\Actions\SetUrlForBookmarksThumbnail;
use App\Http\Filters\FilterByTags;
use App\Http\Requests\Bookmark\StoreBookmarkRequest;
use App\Models\Bookmark;
use App\Models\Context;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as ECollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookmarkService
{
    public function __construct(protected ThumbnailService $thumbnailService) {}

    public function search(string $searchRequest, string $userId)
    {
        $bookmarks = Bookmark::search($searchRequest)->query(function ($builder) {
            $builder->with('tags:id,name,description');
        })->where('user_id', $userId)->get();

        $thumbnailsId = [];

        foreach ($bookmarks as $bookmark) {
            $thumbnailsId[] = $bookmark->thumbnail_id;
        }

        $thumbnails = $this->thumbnailService->getThumbnailsIn($thumbnailsId, ['id', 'name'])->keyBy('id');

        $bookmarks->map(function ($bookmark) use ($thumbnails) {
            $bookmark->thumbnail = $this->thumbnailService->generateUrl($thumbnails[$bookmark->thumbnail_id]);
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

    public function bookmark(string $id, bool $withTags = true): ?Bookmark
    {
        $bookmark = Bookmark::query();

        if ($withTags) {
            $bookmark->with('tags:id,name,description');
        }

        return $bookmark->where('id', $id)->with('thumbnail')->first();
        // dd($bookmark->thumbnail);
        // return $bookmark;
    }

    public function bookmarksIn(array $ids): ?ECollection
    {
        $bookmarks = Bookmark::whereIn('id', $ids)->with('thumbnail')->get();
        // dd($bookmark->thumbnail);
        return $bookmarks;
    }

    public function bookmarksFromContext(string $idContext): Builder
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
        if (!isset($data['order'])) {
            $maxContextsOrder = Context::where('parent_context_id', $data['context_id'])->max('order');
            $maxBookmarksOrder = Bookmark::where('context_id', $data['context_id'])->max('order');
            $data['order'] =
                ($maxContextsOrder > $maxBookmarksOrder ? $maxContextsOrder :
                    $maxBookmarksOrder) + 1;
        }

        $data['order'] += 1;
        $data['user_id'] = $userId;
        $bookmark = Bookmark::create($data);
        $bookmark->thumbnail = Storage::url($bookmark->thumbnail->name);

        if (isset($data['tags'])) {
            $bookmark->tags()->attach($data['tags']);
        }

        return $bookmark;
    }

    public function updateBookmark(
        int $id,
        array $data,
        ?array $tags
    ): ?Bookmark {
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

        $bookmark->thumbnail = Storage::url($bookmark->thumbnail->name);
        return $bookmark;
    }

    public function deleteBookmark(string $id): ?bool
    {
        $result = Bookmark::destroy($id);
        return $result;
    }
}
