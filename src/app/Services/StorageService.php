<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Interfaces\EncodedImageInterface;

class StorageService
{
    public function path(string $file, string $disk = 'public'): ?string
    {
        return Storage::disk($disk)->path($file);
    }

    public function fileExists(string $name, string $disk = 'public')
    {
        return Storage::disk($disk)->exists($name);
    }

    public function getFiles(string $dirName = "", string $disk = "public")
    {
        return Storage::disk($disk)->files($dirName);
    }

    public function save(
        $file,
        string $disk = 'public',
        string $path = 'thumbnails'
    ): string {
        return Storage::disk($disk)->putFile($path, $file);
    }

    public function saveToTemp($file): string
    {
        return Storage::disk('public')->putFile('thumbnails-temp', $file);
    }

    public function saveFromString(
        string $str,
        string $name,
        string $path = "thumbnails/",
        string $disk = "public",
    ): ?string {
        return Storage::disk($disk)->put("$path$name", $str) ? "$path$name" : null;
    }

    public function saveEncodedImage(
        EncodedImageInterface $image,
        string $disk = 'public',
        string $path = 'thumbnails/'
    ): string {
        $extension = match ($image->mediaType()) {
            image_type_to_mime_type(IMAGETYPE_WEBP) => image_type_to_extension(
                IMAGETYPE_WEBP
            ),
            image_type_to_mime_type(IMAGETYPE_PNG) => image_type_to_extension(
                IMAGETYPE_PNG
            ),
            image_type_to_mime_type(IMAGETYPE_JPEG) => image_type_to_extension(
                IMAGETYPE_JPEG
            ),
            image_type_to_mime_type(IMAGETYPE_ICO) => image_type_to_extension(
                IMAGETYPE_ICO
            ),
            default => image_type_to_extension(IMAGETYPE_JPEG),
        };

        $name = md5($image);
        $path = "$path$name$extension";

        if ($this->fileExists($path)) {
            return $path;
        }

        $result = Storage::disk($disk)->put($path, (string)$image);

        return $result ? $path : null;
    }

    public function delete(string $file, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->delete($file);
    }
}
