<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;

class SortContextsAndBookmarks
{
    // change to quick sort?
    public static function mixInOrder(array $contexts, array $bookmarks): array
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
                $contextsCount == 0 ||
                ($contextsCounter > $contextsCount - 1)
            ) {
                $result = array_merge(
                    $result,
                    array_slice($bookmarks, $bookmarksCounter)
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
                $result[] = $bookmarks[$bookmarksCounter];
                $bookmarksCounter += 1;
            }
        }

        return $result;
    }
}
