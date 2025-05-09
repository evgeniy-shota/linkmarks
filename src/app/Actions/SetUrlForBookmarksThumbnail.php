<?php

namespace App\Actions;

use App\Services\ThumbnailService;
use Illuminate\Database\Eloquent\Collection as ECollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SetUrlForBookmarksThumbnail
{
    public static function pathToUrl(Collection $bookmarks): Collection
    {
        return $bookmarks->map(
            function ($value) {
                $value->thumbnail = Storage::url($value->thumbnail);
                return $value;
            }
        );
    }
}
