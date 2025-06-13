<?php

namespace App\Http\Controllers;

use App\Actions\GetFaviconFromGstatic;
use App\Http\Resources\ContextCollection;
use App\Http\Resources\ThumbnailResource;
use App\Models\Context;
use App\Services\BookmarkAutocompleteService;
use App\Services\ThumbnailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdditionalDataController extends Controller
{
    public function __construct(
        private BookmarkAutocompleteService $bookmarkAS
    ) {}

    public function allContexts()
    {
        $contexts = Context::where('user_id', Auth::id())->get();

        return new ContextCollection($contexts);
    }

    public function autocompleteData(Request $request)
    {
        $validated = $request->validate([
            'url' => 'url',
        ]);

        $data = $this->bookmarkAS->getAutocompleteData($validated['url']);

        return response()->json(['data' => $data], 200);
    }

    public function potentialThumbnails(Request $request)
    {
        $validated = $request->validate([
            'url' => 'url',
        ]);

        $parsedUrl = parse_url($validated['url']);

        $thumbnails = $this->bookmarkAS
            ->getSiteThumbnails($validated['url'], $parsedUrl['host']);

        return response()->json([
            'data' => ThumbnailResource::collection($thumbnails)
        ], 200);
    }
}
