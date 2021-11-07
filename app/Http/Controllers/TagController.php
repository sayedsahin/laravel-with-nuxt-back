<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(TagRequest $request)
    {
        $tag = Tag::create([
            'name' => strtolower($request->name),
        ]);
        return response(['id' => $tag->id, 'name' => $tag->name]);
    }
}
