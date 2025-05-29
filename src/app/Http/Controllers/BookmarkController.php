<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bookmark\StoreBookmarkRequest;
use App\Http\Requests\Bookmark\UpdateBookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Jobs\ProcessThumbnail;
use App\Models\Bookmark;
use App\Models\Context;
use App\Models\Thumbnail;
use App\Services\BookmarkService;
use App\Services\ImageService;
use App\Services\ThumbnailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookmarkController extends Controller
{
    public function __construct(
        private BookmarkService $bookmarkService,
        private ThumbnailService $thumbnailService
    ) {}

    public function index(Request $request)
    {
        $bookmarks = $this->bookmarkService->allBookmarks();

        foreach ($bookmarks as $bookmark) {
            $bookmark->thumbnail = $bookmark->thumbnail->name ?
                Storage::url($bookmark->thumbnail->name) : '';
            $bookmark->description = $bookmark->description ?? '';
        }

        return new BookmarkResource($bookmarks);
    }

    public function show(string $id)
    {
        $bookmark = $this->bookmarkService->bookmark($id);

        if ($bookmark) {
            $bookmark->thumbnail = Storage::url($bookmark->thumbnail->name);
        }

        return new BookmarkResource($bookmark);
    }

    public function store(StoreBookmarkRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        if (isset($validated['thumbnailFile'])) {
            $thumbnail = $this->thumbnailService->store($validated['thumbnailFile']);
            $validated['thumbnail_id'] = $thumbnail->id;
            // $file = $this->thumbnailService->saveToTemp($validated['thumbnailFile']);

            // $thumbnail = Thumbnail::create([
            //     'user_id' => Auth::id(),
            //     'name' => $file,
            //     'source' => '',
            //     // 'associations' => '',
            //     // 'is_processed' => '',
            // ]);
            // $validated['thumbnail_id'] = $thumbnail->id;

            // if (ImageService::fileCanProcessed($file)) {
            //     ProcessThumbnail::dispatch($thumbnail);
            // }

            unset($validated['thumbnailFile']);
        } else if (!isset($validated['thumbnail_id'])) {
            $validated['thumbnail_id'] = $this->thumbnailService->getDefault()->id;
        }

        if (!isset($validated['order'])) {
            $maxContextsOrder = Context::where('parent_context_id', $validated['parent_context_id'])->max('order');
            $maxBookmarksOrder = Bookmark::where('context_id', $validated['parent_context_id'])->max('order');
            $validated['order'] =
                ($maxContextsOrder > $maxBookmarksOrder ? $maxContextsOrder :
                    $maxBookmarksOrder) + 1;
        }

        $validated['order'] += 1;
        $bookmark = Bookmark::create($validated);
        $bookmark->thumbnail = Storage::url($bookmark->thumbnail->name);
        return new BookmarkResource($bookmark);
    }

    public function update(UpdateBookmarkRequest $request, string $id)
    {
        $validated = array_filter($request->validated(), function ($item) {
            return isset($item);
        });

        if (isset($validated['thumbnailFile'])) {
            $thumbnail = $this->thumbnailService->store($validated['thumbnailFile']);
            $validated['thumbnail_id'] = $thumbnail->id;

            unset($validated['thumbnailFile']);
        } else if (!isset($validated['thumbnail_id'])) {
            $validated['thumbnail_id'] = $this->thumbnailService->getDefault()->id;
        }

        $result = $this->bookmarkService->updateBookmark($id, $validated);

        if (!$result) {
            return response()->json(['message' => 'Bookmark not updated...'], 400);
        }

        $bookmark = $this->bookmarkService->bookmark($id);

        if ($bookmark) {
            $bookmark->thumbnail = Storage::url($bookmark->thumbnail->name);
        }

        return new BookmarkResource($bookmark);
    }

    public function destroy(string $id)
    {
        $res = $this->bookmarkService->deleteBookmark($id);
        dump($res);

        return response()->json(['message' => 'deleted successfully', 200]);
    }
}
