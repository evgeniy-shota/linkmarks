<?php

namespace App\Http\Controllers\AutofillForms;

use App\Enums\ThumbnailSource;
use App\Http\Controllers\Controller;
use App\Services\HtmlParseService;
use App\Services\HttpQueryService;
use App\Services\ImageService;
use App\Services\ThumbnailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookmarksFormController extends Controller
{
    public function __construct(
        private HttpQueryService $httpQuery,
        private HtmlParseService $htmlParse,
        private ThumbnailService $thumbnailService,
        private ImageService $imageService,
    ) {}
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
        // check if thumbnails exist
        $thumbnails = $this->thumbnailService->getByAssociations($url['host']);

        if (isset($thumbnails)) {
            return response()->json(['data' => [
                'name' => '',
                'thumbnails' => $thumbnails,
            ]], 200);
        }

        $respons = $this->httpQuery->getPage($domain);

        if (!isset($respons)) {
            return response()->json(['message' => 'The resource is not responding'], 200);
        }

        $images = $this->htmlParse->getImages($respons);
        $parsedImageUrl = parse_url($images[0]);
        $imageUrl = isset($parsedImageUrl['scheme'])
            && isset($parsedImageUrl['host'])
            ? $images[0]
            : $domain . $images[0];
        $image = $this->httpQuery->getImage($imageUrl);
        $encoded = $this->imageService->scale($image);
        // $savedImage = $this->thumbnailService->saveFromStr($image, '/thumbnail-temp/' . Str::random(20));
        $savedImage = $this->thumbnailService->saveFromEncodedImage($encoded);
        $thumbnail = $this->thumbnailService->create(
            $savedImage,
            ThumbnailSource::AutoLoad->value,
            $url['host']
        );
        // dd($thumbnail);
        return  response()->json(['data' => [
            'name' => '',
            'thumbnails' => [
                'id' => $thumbnail->id,
                'name' => Storage::disk('public')->url($thumbnail->name),
            ]
        ]], 200);
    }
}
