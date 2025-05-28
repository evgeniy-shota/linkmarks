<?php

namespace App\Http\Controllers;

use App\Actions\SetUrlForBookmarksThumbnail;
use App\Models\Bookmark;
use App\Models\Context;
use App\Services\BookmarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct(protected BookmarkService $bookmarkService) {}

    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => "required|string|min:3|max:30"
        ]);

        $contexts = Context::search($validated['search'])
            ->where('user_id', Auth::id())->get();

        $bookmarks = $this->bookmarkService->search($validated['search'], Auth::id());

        // $bookmarks = Bookmark::search($validated['search'])
        //     ->where('user_id', Auth::id())->leftJoin()->get();

        if (count($contexts) + count($bookmarks) > 0) {
            // $bookmarks = SetUrlForBookmarksThumbnail::pathToUrl($bookmarks);
            $result = array_merge($contexts->toArray(), $bookmarks->toArray());
            return response()->json(
                ['data' => $result, 'message' => 'Nothing found...'],
                200
            );
        }

        return response()->json(
            ['data' => [], 'message' => 'Nothing found...'],
            200
        );
    }
}
