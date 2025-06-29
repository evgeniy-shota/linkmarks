<?php

namespace App\Http\Controllers;

use App\Actions\SetUrlForBookmarksThumbnail;
use App\Actions\SortContextsAndBookmarks;
use App\Http\Filters\FilterByTags;
use App\Http\Filters\FilterContextsByTags;
use App\Http\Requests\Context\ShowDataContextRequest;
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
            ->getContexts($rootContext->id)->get()->toArray();
        $bookmarks = $this->bookmarkService
            ->bookmarksFromContext($rootContext->id)->get();
        $bookmarks =
            SetUrlForBookmarksThumbnail::pathToUrl($bookmarks)->toArray();
        $result = SortContextsAndBookmarks::mixInOrder($contexts, $bookmarks);

        return view(
            'home',
            ['contexts' => $result, 'rootContext' => $rootContext->toArray()]
        );
    }

    public function show(Request $request, int $id)
    {
        $context = $this->contextService->getContext($id);

        if ($request->user()->cannot('view', $context)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return new ContextResource($context);
    }

    public function showContextData(ShowDataContextRequest $request, int $id)
    {
        $context = $this->contextService->getContext($id, false);

        if ($request->user()->cannot('view', $context)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = array_filter($request->validated(), function ($item) {
            return isset($item);
        });

        $filterParams = [];

        if (isset($validated['tagsIncluded'])) {
            $filterParams['tagsIncluded'] = $validated['tagsIncluded'];
        }

        if (isset($validated['tagsExcluded'])) {
            $filterParams['tagsExcluded'] = $validated['tagsExcluded'];
        }

        $contextualFiltration = isset($validated['contextualFiltration']);
        $discardToContexts = isset($validated['discardToContexts']);
        $discardToBookmarks = isset($validated['discardToBookmarks']);

        $contexts = $this->contextService->getFilteredContexts(
            $id,
            Auth::id(),
            $contextualFiltration,
            $discardToContexts,
            $filterParams
        );
        $bookmarks = $this->bookmarkService->getFilteredBookmarks(
            $id,
            Auth::id(),
            $contextualFiltration,
            $discardToBookmarks,
            $filterParams
        );

        $result = SortContextsAndBookmarks::mixInOrder(
            $contexts->toArray(),
            $bookmarks->toArray()
        );

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

        if (isset($validated['tags'])) {
            $context->tags()->attach($validated['tags']);
            // unset($validated['tags']);
        }

        return new ContextResource($context);
    }

    public function update(UpdateContextRequest $request, int $id)
    {
        $context = $this->contextService->getContext($id, false);

        if (!isset($context) || $request->user()->cannot('delete', $context)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validated();
        $tags = null;

        if (isset($validated['tags'])) {
            $tags = $validated['tags'];
            unset($validated['tags']);
        }

        $context = $this->contextService->updateContext($validated, $id, $tags);

        if ($context) {
            // $context->tags()->detach();
            // if (isset($tags)) {
            //     $context->tags()->attach($tags);
            // }
            return new ContextResource($context);
        }

        return response()->json(['message' => 'Update fail'], 400);
    }

    public function destroy(Request $request, int $id)
    {
        $context = $this->contextService->getContext($id, false);

        if (!isset($context) || $request->user()->cannot('delete', $context)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $result = $this->contextService->deleteContext($id);

        if ($result) {
            return response()->json(['message' => 'Delete successful'], 200);
        }

        return response()->json(['message' => 'Delete fail'], 200);
    }
}
