<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bookmark\StoreBookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
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
        $data = array_merge($request->validated(), ['user_id' => Auth::id()]);

        if (isset($data['thumbnail'])) {

            $file = Storage::disk('public')->putFile('thumbnails', $data['thumbnail']);

            $thumbnail = Thumbnail::create([
                'user_id' => Auth::id(),
                'name' => $file,
                'source' => '',
            ]);

            $data['thumbnail_id'] = $thumbnail->id;
        }

        $bookmark = Bookmark::create($data);
        return $bookmark;
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
