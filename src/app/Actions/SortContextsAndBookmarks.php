<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;

class SortContextsAndBookmarks
{
    // change to quick sort?
    public static function mixInOrder(array $contexts, array $bookmarks): array
    {
        if (count($contexts) === 0) {
            return $bookmarks;
        }

        if (count($bookmarks) === 0) {
            return $contexts;
        }

        $result = array_merge($contexts, $bookmarks);
        // $contextsOrders = array_column($contexts, 'order');
        // $bookmarksOrders = array_column($bookmarks, 'order');
        $orders = array_column($result, 'order');

        array_multisort($orders, $result);

        return $result;
    }
}
