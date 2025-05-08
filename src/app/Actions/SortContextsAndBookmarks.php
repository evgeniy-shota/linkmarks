<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;

class SortContextsAndBookmarks
{
    public static function sort($contexts, $bookmarks): array
    {
        $result = [];
        $contextsCounter = 0;
        $bookmarksCounter = 0;
        $contextsCount = count($contexts);
        $bookmarksCount = count($bookmarks);

        while (
            $contextsCounter + $bookmarksCounter !=
            $contextsCount + $bookmarksCount
        ) {

            if (
                $bookmarksCount == 0 ||
                ($bookmarksCounter > $bookmarksCount - 1)
            ) {
                $result = array_merge(
                    $result,
                    array_slice($contexts, $contextsCounter)
                );
                break;
            }

            if (
                $contexts[$contextsCounter]['order']
                < $bookmarks[$bookmarksCounter]['order']
            ) {
                $result[] = $contexts[$contextsCounter];
                $contextsCounter += 1;
            }

            if (
                $contextsCounter > $contextsCount - 1
                || ($contexts[$contextsCounter]['order']
                    > $bookmarks[$bookmarksCounter]['order'])
            ) {
                $bookmarks[$bookmarksCounter]['thumbnail'] =
                    Storage::url($bookmarks[$bookmarksCounter]['thumbnail']['name']);
                $result[] = $bookmarks[$bookmarksCounter];
                $bookmarksCounter += 1;
            }
        }

        return $result;
    }
}
