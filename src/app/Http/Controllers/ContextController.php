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

        $contextData = $this->contextService->showContextData($validated, $id);

        return response()->json(['data' => $contextData], 200);
    }

    public function store(StoreContextRequest $request)
    {
        $validated = $request->validated();
        $context = $this->contextService->createContext($validated, Auth::id());

        return new ContextResource($context);
    }

    public function update(UpdateContextRequest $request, int $id)
    {
        $context = $this->contextService->getContext($id, false);

        if (!isset($context) || $request->user()->cannot('delete', $context)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $context = $this->contextService
            ->updateContext($request->validated(), $id);

        if ($context) {
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
