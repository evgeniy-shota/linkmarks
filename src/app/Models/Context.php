<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Context extends Model
{
    /** @use HasFactory<\Database\Factories\ContextFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'is_root',
        'parent_context_id',
        'enabled',
        'order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }
}
