<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookmarkTag extends Pivot
{
    protected $fillable = [
        'bookmark_id',
        'tag_id',
    ];

    protected $table = "bookmarks_tags";
}
