<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['data' => [
            [
                'id' => 1,
                'name' => 'tag 1'
            ],
            [
                'id' => 2,
                'name' => 'tag 2'
            ],
            [
                'id' => 3,
                'name' => 'tag 3'
            ],
        ]], 200);
    }

    public function show(Request $request, string $id) {}

    public function store(Request $request) {}

    public function update(Request $request, string $id) {}

    public function destroy(Request $request, string $id) {}
}
