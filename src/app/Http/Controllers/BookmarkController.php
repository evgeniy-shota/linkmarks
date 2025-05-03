<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index(Request $request)
    {
        // $bookmarks = Bookmark::all();
        $bookmarks = Bookmark::with('thumbnail')->get();

        foreach ($bookmarks as $bookmark) {
            $bookmark->thumbnail = $bookmark->thumbnail->source;
            $bookmark->description = $bookmark->description ?? '';
        }

        return view('home', ['bookmarks' => $bookmarks]);
    }

    public function show(string $id)
    {
        return dd('show bookmark ' . $id);
    }
}
