<?php

namespace App\Services;

use App\Models\Thumbnail;
use Illuminate\Support\Facades\Storage;

class ThumbnailService
{
    public function generateUrl(string|Thumbnail $data): string
    {
        if (is_string($data)) {
            return Storage::url($data);
        }

        return Storage::url($data->name) ?? '';
    }
}
