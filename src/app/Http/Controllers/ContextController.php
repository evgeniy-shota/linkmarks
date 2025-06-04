<?php

namespace App\Http\Controllers;

use App\Actions\SetUrlForBookmarksThumbnail;
use App\Actions\SortContextsAndBookmarks;
use App\Http\Filters\FilterByTags;
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

    public function show(string $id)
    {
        $context = $this->contextService->getContext($id);

        return new ContextResource($context);
    }

    public function showContextData(ShowDataContextRequest $request, string $id)
    {
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

        if (!isset($validated['contextualFiltration']) && count($filterParams) > 0) {
            $contexts = $this->contextService->getAllContexts(Auth::id());
            $bookmarks = $this->bookmarkService->getAllBookmarks(Auth::id());

            $contextFilter = app()->make(
                FilterByTags::class,
                ['queryParams' => $filterParams, 'tableName' => 'contexts_tags'],
            );
            $bookmarkFilter = app()->make(
                FilterByTags::class,
                ['queryParams' => $filterParams, 'tableName' => 'bookmarks_tags'],

            );
            $contexts = $contexts->filter($contextFilter);
            $bookmarks = $bookmarks->filter($bookmarkFilter);

            $contexts = $contexts->get()->toArray();
            $bookmarks = $bookmarks->get();
        } else {
            $contexts = $this->contextService->getContexts($id);
            $bookmarks = $this->bookmarkService->bookmarksFromContext($id);

            if (count($filterParams) > 0) {
                $contextFilter = app()->make(
                    FilterByTags::class,
                    ['queryParams' => $filterParams, 'tableName' => 'contexts_tags'],
                );
                $bookmarkFilter = app()->make(
                    FilterByTags::class,
                    ['queryParams' => $filterParams, 'tableName' => 'bookmarks_tags'],

                );
                $contexts = $contexts->filter($contextFilter);
                $bookmarks = $bookmarks->filter($bookmarkFilter);
            }

            $contexts = $contexts->get()->toArray();
            $bookmarks = $bookmarks->get();
        }

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

        if (isset($validated['tags'])) {
            $context->tags()->attach($validated['tags']);
            // unset($validated['tags']);
        }

        return new ContextResource($context);
    }

    public function update(UpdateContextRequest $request, string $id)
    {
        $validated = $request->validated();

        if (isset($validated['tags'])) {
            $tags = $validated['tags'];
            unset($validated['tags']);
        }

        $context = $this->contextService->updateContext($validated, $id);

        if ($context) {
            $context->tags()->detach();

            if (isset($tags)) {
                $context->tags()->attach($tags);
            }

            return new ContextResource($context);
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
