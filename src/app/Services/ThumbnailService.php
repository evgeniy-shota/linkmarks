<?php

namespace App\Services;

use App\Models\Thumbnail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class ThumbnailService
{
    public function generateUrl(string|Thumbnail $data): string
    {
        if (is_string($data)) {
            return Storage::url($data);
        }

        return Storage::url($data->name) ?? '';
    }

    public function saveToThumbnails($file)
    {
        return Storage::disk('public')->putFile('thumbnails', $file);
    }

    public function saveToThumbnailsTemp($file)
    {
        $manager = new ImageManager(new Driver());

        $bluredFile = $manager->read($file);
        $bluredFile->blur(5);
        $bluredFile = (string)$bluredFile->toPng();

        return Storage::disk('public')->put('thumbnails-temp', $bluredFile);
    }

    public function convertToWebp()
    {
        $manager = new ImageManager(new Driver());
    }
}
