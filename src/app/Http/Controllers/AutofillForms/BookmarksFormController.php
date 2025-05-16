<?php

namespace App\Http\Controllers\AutofillForms;

use App\Http\Controllers\Controller;
use App\Services\HttpQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookmarksFormController extends Controller
{
    public function __construct(private HttpQueryService $httpQuery) {}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'url' => 'url',
        ]);

        $url = parse_url($validated['url']);
        $domain = $url['scheme'] . '://' . $url['host'];

        $respons = $this->httpQuery->getPage($domain);
        // $images = ;

        Storage::disk('public')->put('response.txt', $respons);

        return  response()->json([], 200);
    }
}
