<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;
use Orchid\Screen\AsSource;

class Context extends Model
{
    /** @use HasFactory<\Database\Factories\ContextFactory> 
     */
    use HasFactory, Searchable, Filterable, AsSource;

    protected $fillable = [
        'user_id',
        'name',
        'is_root',
        'parent_context_id',
        'order',
    ];

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function style(): HasOne
    {
        return $this->hasOne(ContextStyle::class);
    }

    public function thumbnails(): BelongsToMany
    {
        return $this->belongsToMany(Thumbnail::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'contexts_tags');
    }
}
