<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::where('user_id', Auth::id())->get();
        return new TagCollection($tags);
    }

    public function show(Request $request, string $id)
    {
        $tag = Tag::find($id);

        if ($request->user()->cannot('view', $tag)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return new TagResource($tag);
    }

    public function store(TagRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $tag = Tag::create($data);

        return new TagResource($tag);
    }

    public function update(TagRequest $request, string $id)
    {
        $tag  = Tag::where('id', $id)->first();

        if ($request->user()->cannot('update', $tag)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validated();

        $tag->update($validated);
        return new TagResource($tag);
    }

    public function destroy(Request $request, string $id)
    {
        $tag = Tag::find($id);

        if ($request->user()->cannot('delete', $tag)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $tag = $tag->delete();

        if ($tag) {
            return response()->json(['message' => 'Tag delete succesfull'], 200);
        }

        return response()->json(['message' => 'Fail! Tag not deleted'], 400);
    }
}
