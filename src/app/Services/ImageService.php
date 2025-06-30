<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\EncodedImage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Imagick;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;

class ImageService
{

    const SUPPORTED_FORMATS = [
        "image/webp" => 'webp',
        "image/png" => 'png',
        "image/jpeg" => "jpeg",
    ];

    public function getImageExtensionFromString(string $file)
    {
        // $fileInfo = finfo_buffer(finfo_open(), $file, FILEINFO_MIME_TYPE);
        $fileInfo = getimagesizefromstring($file);

        return isset($fileInfo[2]) ? image_type_to_extension($fileInfo[2]) : null;
    }

    public function scale(string $img, int $size = 90): ?EncodedImageInterface
    {
        $manager = ImageManager::imagick();
        try {
            $image = $manager->read($img);
        } catch (Exception $e) {
            return null;
        }

        $imagick = $image->core()->native();
        $type =  $imagick->getImageMimeType();

        $extension = $this->getSupportedExtension($type);

        if (!$extension) {
            return null;
        }

        $imagick->stripImage();

        if ($imagick->getNumberImages() > 1) {
            $image = $manager->read($this->getImageFromList($imagick));
        }

        if ($image->height() > $size || $image->width() > $size) {
            if ($image->height() > $image->width()) {
                $image->scale(height: $size);
            } else {
                $image->scale(width: $size);
            }
        }

        $encoded = $image->encodeByExtension($extension, quality: 85);
        return $encoded;
    }

    public function convertToWebp(string $path, $quality = 20): EncodedImageInterface
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($path);
        $encoded = $image->toWebp($quality);

        return $encoded;
    }

    public function getSupportedExtension(string $type): ?string
    {
        // $arrayExt = [
        //     image_type_to_mime_type(IMAGETYPE_WEBP) => "webp",
        //     image_type_to_mime_type(IMAGETYPE_PNG) => "png",
        //     image_type_to_mime_type(IMAGETYPE_ICO) => "png",
        //     image_type_to_mime_type(IMAGETYPE_JPEG) => "jpeg",
        //     "image/x-ico" => "webp",
        // ];

        $extension = match ($type) {
            image_type_to_mime_type(IMAGETYPE_WEBP) => 'webp',
            image_type_to_mime_type(IMAGETYPE_PNG) => 'png',
            image_type_to_mime_type(IMAGETYPE_JPEG) => "jpeg",
            // image_type_to_mime_type(IMAGETYPE_JPEG) => image_type_to_extension(IMAGETYPE_JPEG),
            default => null,
        };

        return $extension;
    }

    public static function fileCanProcessed(string $absolutePath): bool
    {
        $type = Storage::disk('public')->mimeType($absolutePath);
        return array_key_exists($type, self::SUPPORTED_FORMATS);
    }

    private function getImageFromList($imagick)
    {
        // $imagick->setLastIterator();
        $image = null;        // if can't recognize image extension remove image
        // $thumbnailsNames = array_map(function ($item) {
        //     return md5($item) . $this->imageService->getImageExtensionFromString($item);
        // }, $images);
        $size = 0;
        $imgCount = $imagick->getNumberImages();
        for ($i = 0; $i < $imgCount; $i++) {
            $currentImageSize = $imagick->height + $imagick->width;

            if ($currentImageSize > $size) {
                $image = $imagick->getImage();
                $size = $currentImageSize;
            }

            $imagick->previousImage();
        }
        return $image;
    }

    public function encodeFromString(string $str): EncodedImageInterface
    {
        $manager = ImageManager::imagick();
        $image = $manager->read($str);
        $encoded = $image->encode();
        return $encoded;
    }
}
