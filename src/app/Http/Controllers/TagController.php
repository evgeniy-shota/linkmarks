<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::where('user_id', Auth::id())->where('is_enabled', true)->get();
        return new TagCollection($tags);
    }

    public function show(Request $request, string $id) {}

    public function store(Request $request) {}

    public function update(Request $request, string $id) {}

    public function destroy(Request $request, string $id) {}
}
