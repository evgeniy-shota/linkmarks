<?php

namespace App\Http\Controllers;

use App\Actions\SetUrlForBookmarksThumbnail;
use App\Actions\SortContextsAndBookmarks;
use App\Http\Requests\Context\StoreContext;
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
            ['contexts' => $result, 'rootContext' => $rootContext->toArray()]
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

    public function store(StoreContext $request)
    {
        $validated = $request->validated();

        if (!isset($validated['order'])) {
            $maxContextsOrder = Context::where('parent_context_id', $validated['parent_context_id'])->max('order');
            $maxBookmarksOrder = Bookmark::where('context_id', $validated['parent_context_id'])->max('order');
            $validated['order'] =
                ($maxContextsOrder > $maxBookmarksOrder ? $maxContextsOrder :
                    $maxBookmarksOrder) + 1;
        }

        $validated['order'] += 1;
        $context = $this->contextService->createContext($validated, Auth::id());

        return new ContextResource($context);
    }

    public function update(Request $request, string $id) {}

    public function destroy(string $id)
    {
        $result = $this->contextService->deleteContext($id);

        return response()->json(['message' => 'Context delete successful'], 200);
    }
}
