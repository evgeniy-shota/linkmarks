<?php

namespace App\Http\Controllers;

use App\Actions\SetUrlForBookmarksThumbnail;
use App\Models\Bookmark;
use App\Models\Context;
use App\Services\BookmarkService;
use App\Services\ContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function __construct(protected ContextService $contextService, protected BookmarkService $bookmarkService) {}

    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => "required|string|min:2|max:30"
        ]);

        $contexts = $this->contextService->search($validated['search'], Auth::id());
        $bookmarks = $this->bookmarkService->search($validated['search'], Auth::id());

        if (count($contexts) + count($bookmarks) > 0) {
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
