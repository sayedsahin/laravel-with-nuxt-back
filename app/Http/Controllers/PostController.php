<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Topic;

class PostController extends Controller
{
    public function show($topic, Post $post)
    {
        return new PostResource($post);
    }

    public function store(PostCreateRequest $request, Topic $topic)
    {
        $post = new Post;
        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->posts()->save($post);
        return new PostResource($post);
    }

    

    public function update(PostUpdateRequest $request, Topic $topic, Post $post)
    {
        $this->authorize('update', $post);
        $post->body = $request->get('body', $post->body);
        $post->save();
        return new PostResource($post);
    }

    

    public function destroy(Topic $topic, Post $post)
    {
        $this->authorize('destroy', $post);
        $post->delete();
        return response(null, 204);
    }
}
