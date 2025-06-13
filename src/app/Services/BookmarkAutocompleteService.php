<?php

namespace App\Services;

use App\Actions\GetFaviconFromGstatic;
use App\Enums\ThumbnailSource;
use App\Http\Resources\ThumbnailResource;
use App\Jobs\ProcessThumbnail;
use App\Models\Thumbnail;
use Illuminate\Support\Facades\Auth;

class BookmarkAutocompleteService
{
    public function __construct(
        protected StorageService $storageService,
        protected ThumbnailService $thumbnailService,
        protected ImageService $imageService,
        protected GetFaviconFromGstatic $getFaviconGstatic,
        protected HtmlParseService $htmlParse,
        protected HttpQueryService $httpQuery,
    ) {}

    public function getAutocompleteData(string $url)
    {
        $errors = [];
        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $page = $this->httpQuery->getPage($url);
        // $this->httpQuery->getPage($domain);

        if (!isset($page)) {
            $title = $parsedUrl['host'];
            $errors['Autocomplete'] = "Resource not answer. Probably we can't get all information for autocomplete.";
        } else {
            $title = $this->htmlParse->getTitle($page) ?? $parsedUrl['host'];
        }

        $thumbnails = $this->thumbnailService->getByAssociations($parsedUrl['host'], Auth::id());

        if (isset($thumbnails) && count($thumbnails) > 0) {
            return [
                'errors' => $errors,
                'name' => $title,
                'thumbnails' => ThumbnailResource::collection($thumbnails),
            ];
        }

        $thumbnail = $this->getSiteFavicon($url, $parsedUrl['host']);

        if (isset($thumbnail)) {
            return [
                'errors' => $errors,
                'name' => $title,
                'thumbnails' => [new ThumbnailResource($thumbnail)],
            ];
        }

        if (isset($page)) {
            $thumbnails = $this->getImagesFromPage($page, $domain, $parsedUrl['host']);

            if (isset($thumbnails) && count($thumbnails) > 0) {
                return [
                    'errors' => $errors,
                    'name' => $title,
                    'thumbnails' => ThumbnailResource::collection($thumbnails),
                ];
            }
        }

        $thumbnail = $this->thumbnailService->getDefault(false);
        $errors['thumbnails'] = "We probably couldn't find a thumbnail for this site. Temporarily setting the default thumbnail.";

        return [
            'errors' => $errors,
            'name' => $title,
            'thumbnails' => [new ThumbnailResource($thumbnail)],
        ];

        // new ThumbnailResource($thumbnails)
    }

    public function getSiteThumbnails(string $url, string $associations = "")
    {
        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
        $associations = $associations ?? $parsedUrl['host'];
        $associationThumbnails =
            $this->getSiteThumbnailsAssociations($associations);
        $thumbnails = $associationThumbnails;

        if (!$associationThumbnails->contains(function ($item) {
            return $item->source === ThumbnailSource::Gstatic->value;
        })) {
            $favicon = $this->getSiteFavicon($url, $parsedUrl['host']);

            if (isset($favicon)) {
                $thumbnails =  $thumbnails->concat([$favicon]);
            }
        }

        $page = $this->httpQuery->getPage($url);

        if (isset($page)) {
            $imagesFromSite = collect(
                $this->getImagesFromPage($page, $domain, $parsedUrl['host'])
            );
            $thumbnails = $thumbnails->concat($imagesFromSite);
        }

        $thumbnails = $thumbnails->unique('name');

        return count($thumbnails) > 0 ? $thumbnails : array($this->thumbnailService->getDefault(false));
    }

    public function getSiteFavicon(string $url, string $host): ?Thumbnail
    {
        $favicon = $this->getFaviconGstatic->getFavicon($url) ?? null;

        if (!isset($favicon)) {
            return null;
        }

        $faviconExtension = $this->imageService->getImageExtensionFromString($favicon);

        if (!isset($faviconExtension)) {
            return null;
        }

        $thumbnailName = md5($favicon) . $faviconExtension;
        $thumbnailFile = $this->storageService->saveFromString(
            $favicon,
            $thumbnailName
        );

        $thumbnail = $this->thumbnailService->create(
            $thumbnailFile,
            Auth::id(),
            ThumbnailSource::Gstatic->value,
            $host,
        );

        if (ImageService::fileCanProcessed($thumbnailFile)) {
            ProcessThumbnail::dispatch($thumbnail);
        }

        return $thumbnail;
    }

    public function getSiteThumbnailsAssociations(string $associations)
    {
        $thumbnails = $this->thumbnailService->getByAssociations($associations, Auth::id());
        return $thumbnails;
    }

    public function getImagesFromPage($page, string $domain, string $host)
    {
        $parsedImages = $this->htmlParse->getImages($page, $domain);

        if (count($parsedImages) == 0) {
            return null;
        }

        $images = $this->httpQuery->poolImages(array_column($parsedImages, 'path'));
        $thumbnailsFiles = [];

        for ($i = 0, $size = count($images); $i < $size; $i++) {
            $encodedImage = $this->imageService->scale($images[$i]);

            if ($encodedImage) {
                $thumbnailsFiles[] = $this->storageService->saveEncodedImage($encodedImage);
            } else {
                $name = md5($images[$i]);
                $extension = $this->imageService->getImageExtensionFromString(
                    $images[$i]
                );

                if (!isset($extension)) {
                    unset($images[$i]);
                    continue;
                }

                $thumbnailsFiles[] = $this->storageService->saveFromString(
                    $images[$i],
                    $name . $extension
                );
            }
        }

        $thumbnails = $this->thumbnailService->massCreateIfNotExist(
            $thumbnailsFiles,
            Auth::id(),
            ThumbnailSource::AutoLoad->value,
            $host,
        );

        return $thumbnails;
    }
}
