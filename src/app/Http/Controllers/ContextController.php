<?php

namespace App\Http\Controllers;

use App\Actions\SetUrlForBookmarksThumbnail;
use App\Actions\SortContextsAndBookmarks;
use App\Http\Requests\Context\StoreContextRequest;
use App\Http\Requests\Context\UpdateContextRequest;
use App\Http\Resources\ContextResource;
use App\Models\Bookmark;
use App\Models\Context;
use App\Services\BookmarkService;
use App\Services\ContextService;
use Illuminate\Auth\Events\Validated;
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

    public function showContextData(Request $request, string $id)
    {
        $validated = array_filter($request->validate([
            "tagsIncluded" => 'nullable|array',
            "tagsExcluded" => 'nullable|array',
        ]), function ($item) {
            return isset($item);
        });

        $contexts = $this->contextService->getContexts($id)->toArray();
        $bookmarks = $this->bookmarkService->bookmarksFromContext($id);

        $bookmarks =
            SetUrlForBookmarksThumbnail::pathToUrl($bookmarks)->toArray();

        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);

        return response()->json(['data' => $result], 200);
    }

    public function store(StoreContextRequest $request)
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

    public function update(UpdateContextRequest $request, string $id)
    {
        $validated = $request->validated();
        $result = $this->contextService->updateContext($validated, $id);

        if ($result) {
            return new ContextResource($this->contextService->getContext($id));
        }

        return response()->json(['message' => 'Update fail'], 400);
    }

    public function destroy(string $id)
    {
        $result = $this->contextService->deleteContext($id);

        if ($result) {
            return response()->json(['message' => 'Delete successful'], 200);
        }

        return response()->json(['message' => 'Delete fail'], 200);
    }
}
