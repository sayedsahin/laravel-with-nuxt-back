<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagTopicsResource;
use App\Http\Resources\TagsResource;
use App\Http\Resources\TopicsResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $skip = ($request->get('page') - 1) * 200;
        $tags = Tag::withCount('topics')->take(200)->skip($skip)->orderBy('name')->get();
        return TagsResource::collection($tags);
    }

    public function show(Tag $tag)
    {
        // $data = Tag::where('id', $tag->id)->withCount('topics')->with(['topics' => fn ($query) => $query->orderBy('id', 'desc')])->get();

        $data = $tag->where('id', $tag->id)->withCount('topics')->first();
        return new TagTopicsResource($data);
    }

    public function topics(Request $request, Tag $tag)
    {
        $topics = $tag->topics()
        ->take(10)
        ->skip(($request->get('page') - 1) * 10)
        ->orderBy('id', 'desc')
        ->get();

        return TopicsResource::collection($topics);
    }

    public function store(TagRequest $request)
    {
        $tag = Tag::create([
            'name' => strtolower(str_replace(' ', '-', $request->name) ),
        ]);
        return response(['id' => $tag->id, 'name' => $tag->name]);
    }
}
