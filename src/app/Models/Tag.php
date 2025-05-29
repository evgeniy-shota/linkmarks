<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'description',
        'is_enabled',
        'style',
    ];

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(Bookmark::class, 'bookmarks_tags');
    }

    public function contexts(): BelongsToMany
    {
        return $this->belongsToMany(Context::class, 'contexts_tags');
    }
}
