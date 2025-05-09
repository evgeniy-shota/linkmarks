<?php

namespace App\Http\Controllers;

use App\Actions\SetUrlForBookmarksThumbnail;
use App\Actions\SortContextsAndBookmarks;
use App\Http\Resources\ContextResource;
use App\Models\Bookmark;
use App\Models\Context;
use App\Services\BookmarkService;
use App\Services\ContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContextController extends Controller
{
    public function __construct(
        private ContextService $contextService,
        private BookmarkService $bookmarkService
    ) {
        // parent::__construct();
    }

    public function index()
    {
        $rootContext = $this->contextService->getRootContext(Auth::id());
        $contexts = $this->contextService
            ->getContexts($rootContext->id)->toArray();
        $bookmarks = $this->bookmarkService
            ->bookmarksFromContext($rootContext->id);
        $bookmarks =
            SetUrlForBookmarksThumbnail::pathToUrl($bookmarks)->toArray();
        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);

        return view(
            'home',
            ['contexts' => $result, 'rootContext' => $rootContext->id]
        );
    }

    public function show(string $id)
    {
        $context = $this->contextService->getContext($id);

        return new ContextResource($context);
    }

    public function showContextData(string $id)
    {
        $contexts = $this->contextService->getContexts($id)->toArray();
        $bookmarks = $this->bookmarkService->bookmarksFromContext($id);

        $bookmarks =
            SetUrlForBookmarksThumbnail::pathToUrl($bookmarks)->toArray();

        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);

        return response()->json(['data' => $result], 200);
    }

    public function store(Request $request) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id)
    {
        $result = $this->contextService->deleteContext($id);

        return response()->json(['message' => 'Context delete successful'], 200);
    }
}
