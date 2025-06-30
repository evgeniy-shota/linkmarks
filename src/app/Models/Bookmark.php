<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

/**
 * @property \App\Models\Thumbnail|string $thumbnail
 */
class Bookmark extends Model
{
    /** @use HasFactory<\Database\Factories\BookmarkFactory> */
    use HasFactory, Searchable, Filterable;

    protected $table = 'bookmarks';

    protected $fillable = [
        'user_id',
        'context_id',
        'link',
        'name',
        'thumbnail_id',
        'is_enabled',
        'order',
    ];

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'link' => $this->link,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function context(): BelongsTo
    {
        return $this->belongsTo(Context::class);
    }

    public function thumbnail(): BelongsTo
    {
        return $this->BelongsTo(Thumbnail::class);
    }

    public function style(): HasOne
    {
        return $this->hasOne(BookmarkStyle::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'bookmarks_tags');
    }
}
