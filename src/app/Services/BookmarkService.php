<?php

namespace App\Services;

use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Collection as ECollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookmarkService
{
    public function allBookmarks(): ECollection
    {
        return Bookmark::all();
    }

    public function bookmark(string $id): ?Bookmark
    {
        $bookmark = Bookmark::where('id', $id)->with('thumbnail')->first();
        // dd($bookmark->thumbnail);
        return $bookmark;
    }

    public function bookmarksFromContext(string $idContext): ?Collection
    {
        // $bookmarks = DB::table('bookmarks as bs')
        //     ->leftJoin('thumbnails as ts', 'bs.thumbnail_id', '=', 'ts.id')
        //     ->select('bs.id', 'bs.context_id', 'bs.link', 'bs.name', 'bs.thumbnail_id', 'bs.order', 'ts.name as thumbnail')
        //     ->where('bs.context_id', $idContext)
        //     ->get();

        $bookmarks = Bookmark::select(
            'bookmarks.id',
            'bookmarks.context_id',
            'bookmarks.link',
            'bookmarks.name',
            'bookmarks.thumbnail_id',
            'bookmarks.order',
            'thumbnails.name as thumbnail'
        )->leftJoin('thumbnails', 'bookmarks.thumbnail_id', '=', 'thumbnails.id')
            ->where('bookmarks.context_id', $idContext)
            ->get();

        // dd($bookmarks);
        return $bookmarks;
    }

    // public function bookmarksWithThumbnail(string $idContext): ?ECollection
    // {
    //     $bookmarks = Bookmark::where('context_id', $idContext)->with('thumbnail')
    //         ->orderBy('order')->get();
    //     return $bookmarks;
    // }

    public function updateBookmark(string $id, array $data): bool
    {
        $bookmark = Bookmark::where('id', $id)->update($data);

        if ($bookmark) {
            return true;
        }

        return false;
    }

    public function deleteBookmark(string $id): ?bool
    {
        $result = Bookmark::destroy($id);
        return $result;
    }
}
