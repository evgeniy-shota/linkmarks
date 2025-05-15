<?php

namespace App\Services;

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\EncodedImage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Intervention\Image\Interfaces\ImageInterface;

class ImageService
{
    public function scale(string $img, int $size = 90)
    {
        // $manager = new ImageManager(new Driver);
        $manager = ImageManager::withDriver(new Driver);
        $image = $manager->read($img);
        $imagick = $image->core()->native();
        dump($imagick->getFormat());
        dump('----');
        dd();
        if ($image->height() > $image->width()) {
            $image->scale(height: $size);
        } else {
            $image->scale(width: $size);
        }
        dd($this->getSupportedExtension($type));
        $encoded = $image->encodeByExtension(quality: 80);
        return $encoded;
    }

    public function convertToWebp(string $path, $quality = 20): EncodedImage
    {
        // dd(Storage::disk('public')->path('') . 'thumbnails/');
        $manager = new ImageManager(new Driver());
        $image = $manager->read($path);
        $encoded = $image->toWebp($quality);

        // $filePath = Storage::disk('public')->put($newPath, $encoded);
        // dump($newPath);
        // dd($filePath);
        return $encoded;
    }

    public function getSupportedExtension(string $type): string
    {
        $extension = match ($type) {
            image_type_to_mime_type(IMAGETYPE_WEBP) => image_type_to_extension(IMAGETYPE_WEBP),
            image_type_to_mime_type(IMAGETYPE_PNG) => image_type_to_extension(IMAGETYPE_PNG),
            // image_type_to_mime_type(IMAGETYPE_JPEG) => image_type_to_extension(IMAGETYPE_JPEG),
            // image_type_to_mime_type(IMAGETYPE_ICO) => image_type_to_extension(IMAGETYPE_PNG),
            default => image_type_to_extension(IMAGETYPE_JPEG),
        };

        return $extension;
    }
}
