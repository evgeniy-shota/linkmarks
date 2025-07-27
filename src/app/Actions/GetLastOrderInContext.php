<?php

namespace App\Actions;

use App\Models\Bookmark;
use App\Models\Context;

class GetLastOrderInContext
{

    public static function getOrder(?int $contextId): int
    {
        if (!isset($contextId)) {
            return 0;
        }

        $bookmarksOrder = (int)Bookmark::where('context_id', $contextId)
            ->select('order')->max('order');
        $contextOrder = (int)Context::where('parent_context_id', $contextId)
            ->select('order')->max('order');

        return $bookmarksOrder > $contextOrder ? $bookmarksOrder : $contextOrder;
    }
}
