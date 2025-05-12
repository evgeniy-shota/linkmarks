<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bookmark\StoreBookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use App\Models\Context;
use App\Models\Thumbnail;
use App\Services\BookmarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookmarkController extends Controller
{
    public function __construct(private BookmarkService $bookmarkService) {}

    public function index(Request $request)
    {
        // $bookmarks = Bookmark::all();
        $bookmarks = $this->bookmarkService->allBookmarks();

        foreach ($bookmarks as $bookmark) {
            $bookmark->thumbnail = $bookmark->thumbnail->name ?
                Storage::url($bookmark->thumbnail->name) : '';
            $bookmark->description = $bookmark->description ?? '';
        }

        return new BookmarkResource($bookmarks);
        // return view('home', ['bookmarks' => $bookmarks]);
    }

    public function show(string $id)
    {
        $bookmark = $this->bookmarkService->bookmark($id);

        if ($bookmark) {
            $bookmark->thumbnail = $bookmark->thumbnail->name;
        }

        return new BookmarkResource($bookmark);
    }

    public function store(StoreBookmarkRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        if (isset($validated['thumbnail'])) {

            $file = Storage::disk('public')->putFile('thumbnails', $validated['thumbnail']);

            $thumbnail = Thumbnail::create([
                'user_id' => Auth::id(),
                'name' => $file,
                'source' => '',
            ]);

            $validated['thumbnail_id'] = $thumbnail->id;
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
        $bookmark->thumbnail = $bookmark->thumbnail->name;
        return new BookmarkResource($bookmark);
    }

    public function update(Request $request, string $id)
    {
        dd('try update: ' . $id);
    }

    public function destroy(string $id)
    {
        Bookmark::destroy($id);

        return response()->json(['message' => 'deleted successfully', 200]);
    }
}
