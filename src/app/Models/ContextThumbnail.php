<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ContextThumbnail extends Pivot
{
    protected $fillable = [
        'context_id',
        'thumbnail_id',
        'seted_by_user',
    ];

    protected $table = 'contexts_thumbnails';

    public function context(): BelongsTo
    {
        return $this->belongsTo(Context::class);
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(Thumbnail::class);
    }
}
