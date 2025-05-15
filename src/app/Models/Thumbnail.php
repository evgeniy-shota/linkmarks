<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Thumbnail extends Model
{
    /** @use HasFactory<\Database\Factories\ThumbnailFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'source',
        'associations',
        'is_processed',
        'is_enabled',
    ];

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    // protected function name(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn(string $value) => Storage::url($value),
    //     );
    // }
}
