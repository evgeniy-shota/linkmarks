<?php

namespace App\Services;

use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Collection;

class BookmarkService
{
    public function allBookmarks(): Collection
    {
        return Bookmark::all();
    }

    public function bookmark(int $id): ?Bookmark
    {
        return Bookmark::where('id', $id)->with('thumbnail')->get();
    }

    public function bookmarksFromContext(int $idContext): ?Collection
    {
        $bookmarks = Bookmark::where('context_id', $idContext)->with('thumbnail:id,name')
            ->orderBy('order')->get();
        return $bookmarks;
    }

    public function updateBookmark(array $data, int $id): bool
    {
        $result = Bookmark::update(['id' => $id], $data);
        return $result;
    }

    public function deleteBookmark(int $id): ?bool
    {
        $result = Bookmark::destroy($id);
        return $result;
    }
}
