<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thumbnail extends Model
{
    /** @use HasFactory<\Database\Factories\ThumbnailFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'source',
        'is_enabled',
    ];

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }
}
