<?php

namespace App\Services;

use App\Models\Thumbnail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Support\Str;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncodedImageInterface;

class ThumbnailService
{
    public function generateUrl(string|Thumbnail $data): string
    {
        if (is_string($data)) {
            return Storage::url($data);
        }

        return Storage::url($data->name) ?? '';
    }

    public function save($file): string
    {
        return Storage::disk('public')->putFile('thumbnails', $file);
    }

    public function saveToTemp($file): string
    {
        return Storage::disk('public')->putFile('thumbnails-temp', $file);
    }

    public function saveFromStr(string $str, string $name): string
    {
        dump("name: $name");
        return Storage::disk('public')->put("/thumbnails/$name", $str,);
    }

    public function saveFromEncodedImage(EncodedImageInterface $image): string
    {
        $extension = match ($image->mediaType()) {
            image_type_to_mime_type(IMAGETYPE_WEBP) => image_type_to_extension(IMAGETYPE_WEBP),
            image_type_to_mime_type(IMAGETYPE_PNG) => image_type_to_extension(IMAGETYPE_PNG),
            image_type_to_mime_type(IMAGETYPE_JPEG) => image_type_to_extension(IMAGETYPE_JPEG),
            image_type_to_mime_type(IMAGETYPE_ICO) => image_type_to_extension(IMAGETYPE_ICO),
            default => image_type_to_extension(IMAGETYPE_JPEG),
        };

        $name = md5($image);
        $path = "thumbnails/$name$extension";
        $result = Storage::disk('public')->put($path, (string)$image);

        return $result ? $path : null;
    }

    public function update(string $id, array $data)
    {
        $thumbnail = Thumbnail::where('id', $id)->update($data);
        return $thumbnail;
    }

    // public function convertToWebp(string $path)
    // {
    //     $manager = new ImageManager(new Driver());

    //     $bluredFile = $manager->read($path);
    //     $bluredFile->blur(5);
    //     $bluredFile = (string)$bluredFile->toPng();
    // }
}
