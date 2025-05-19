<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookmarkStyle extends Model
{
    protected $fillable = [
        'bookmark_id',
        'border_style',
        'border_color',
    ];

    protected $table = 'bookmark_styles';

    public function bookmark(): BelongsTo
    {
        return $this->belongsTo(Bookmark::class);
    }
}
