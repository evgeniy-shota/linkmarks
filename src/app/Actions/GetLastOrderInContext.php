<?php

namespace App\Actions;

use App\Models\Bookmark;
use App\Models\Context;

class GetLastOrderInContext
{

    public static function getOrder(string $contextId)
    {
        $bookmarksOrder = Bookmark::where('context_id', $contextId)->max('order');
        $contextOrder = Context::where('parent_context_id', $contextId)->max('order');

        return $bookmarksOrder > $contextOrder ? $bookmarksOrder : $contextOrder;
    }
}
