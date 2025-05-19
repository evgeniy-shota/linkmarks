<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ContextTag extends Pivot
{
    protected $fillable = [
        'context_id',
        'tag_id',
    ];

    protected $table = "contexts_tags";
}
