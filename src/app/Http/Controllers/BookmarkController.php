<?php

namespace App\Http\Controllers;

use App\Enums\ThumbnailSource;
use App\Http\Requests\Bookmark\StoreBookmarkRequest;
use App\Http\Requests\Bookmark\UpdateBookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Jobs\ProcessThumbnail;
use App\Models\Bookmark;
use App\Models\Context;
use App\Models\Tag;
use App\Models\Thumbnail;
use App\Services\BookmarkService;
use App\Services\ImageService;
use App\Services\StorageService;
use App\Services\ThumbnailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookmarkController extends Controller
{
    public function __construct(
        private BookmarkService $bookmarkService,
        private ThumbnailService $thumbnailService,
        private StorageService $storageService,
    ) {}

    public function show(string $id)
    {
        $bookmark = $this->bookmarkService->bookmark($id);

        if ($bookmark) {
            $bookmark->thumbnail = Storage::url($bookmark->thumbnail?->name);
        }

        return new BookmarkResource($bookmark);
    }

    public function store(StoreBookmarkRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        if (isset($validated['thumbnailFile'])) {
            $parsedLink = parse_url($validated['link']);
            $thumbnailFile = $this->storageService
                ->save($validated['thumbnailFile']);
            $thumbnail = $this->thumbnailService->create(
                $thumbnailFile,
                Auth::id(),
                ThumbnailSource::UserLoad->value,
                $parsedLink['host'] ?? '',
            );
            $validated['thumbnail_id'] = $thumbnail->id;

            if (ImageService::fileCanProcessed($thumbnailFile)) {
                ProcessThumbnail::dispatch($thumbnail);
            }

            unset($validated['thumbnailFile']);
        } else if (!isset($validated['thumbnail_id'])) {
            $validated['thumbnail_id'] = $this->thumbnailService
                ->getDefault()->id;
        }

        $bookmark = $this->bookmarkService->store($validated);
        return new BookmarkResource($bookmark);
    }

    public function update(UpdateBookmarkRequest $request, string $id)
    {
        $validated = array_filter($request->validated(), function ($item) {
            return isset($item);
        });

        if (isset($validated['thumbnailFile'])) {
            $parsedLink = parse_url($validated['link']);
            $thumbnailFile = $this->storageService
                ->save($validated['thumbnailFile']);
            $thumbnail = $this->thumbnailService->create(
                $thumbnailFile,
                Auth::id(),
                ThumbnailSource::UserLoad->value,
                $parsedLink['host'] ?? '',
            );
            $validated['thumbnail_id'] = $thumbnail->id;

            if (ImageService::fileCanProcessed($thumbnailFile)) {
                ProcessThumbnail::dispatch($thumbnail);
            }

            unset($validated['thumbnailFile']);
        } else if (!isset($validated['thumbnail_id'])) {
            $validated['thumbnail_id'] = $this->thumbnailService
                ->getDefault()->id;
        }

        if (isset($validated['tags'])) {
            $tags = $validated['tags'];
            unset($validated['tags']);
        }

        $bookmark = $this->bookmarkService->updateBookmark($id, $validated);

        if ($bookmark) {
            $bookmark->tags()->detach();

            if (isset($tags)) {
                $bookmark->tags()->attach($tags);
            }

            $bookmark->thumbnail = Storage::url($bookmark->thumbnail->name);

            return new BookmarkResource($bookmark);
        }

        return response()->json(['message' => 'Bookmark not updated...'], 400);
    }

    public function destroy(string $id)
    {
        $res = $this->bookmarkService->deleteBookmark($id);
        dump($res);

        return response()->json(['message' => 'deleted successfully', 200]);
    }
}
