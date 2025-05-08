<?php

namespace App\Http\Controllers;

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
        $rootContext = $this->contextService->rootContext(Auth::id());
        $currentContexts = $this->contextService->contexts($rootContext->id)->toArray();
        $currentBookmarks = $this->bookmarkService->bookmarksFromContext($rootContext->id)->toArray();

        $result = SortContextsAndBookmarks::sort($currentContexts, $currentBookmarks);

        return view('home', ['contexts' => $result, 'rootContext' => $rootContext->id]);
    }

    public function show(string $id)
    {
        $context = $this->contextService->context($id);

        return new ContextResource($context);
    }

    public function store(Request $request) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id)
    {
        $result = $this->contextService->deleteContext($id);

        return response()->json(['message' => 'Context delete successful'], 200);
    }
}
