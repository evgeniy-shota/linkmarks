<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContextStyle extends Model
{
    protected $fillable = [
        'border_style',
        'border_color',
    ];

    protected $table = "context_styles";

    public function context(): BelongsTo
    {
        return $this->belongsTo(Context::class);
    }
}
