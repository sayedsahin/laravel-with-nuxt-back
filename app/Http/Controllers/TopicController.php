<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicCreateRequest;
use App\Http\Requests\TopicUpdateRequest;
use App\Http\Resources\TopicResource;
use App\Http\Resources\TopicsResource;
use App\Models\Post;
use App\Models\Topic;

class TopicController extends Controller
{
    public function index()
    {
        $topic = Topic::latest()->paginate(10);
        return TopicsResource::collection($topic);
    }
    public function store(TopicCreateRequest $request)
    {
        //My Code System
        /*$topic = Topic::create([
                    'title' => $request->title,
                    'user_id' => $request->user()->id,
                ]);
        $post = Post::create([
                    'body' => $request->body,
                    'topic_id' => $topic->id,
                    'user_id' => $topic->user_id,
                ]);*/

        //Alternative
        $topic = new Topic;
        $topic->title = $request->title;
        $topic->user()->associate($request->user());

        $post = new Post;
        $post->body = $request->body;
        $post->user()->associate($request->user());
        
        $topic->save();
        $topic->posts()->save($post);

        return new TopicResource($topic);
    }
    public function show(Topic $topic)
    {
        return new TopicResource($topic);
    }
    public function update(TopicUpdateRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->title = $request->get('title', $topic->title);
        $topic->save();
        return new TopicResource($topic);
    }
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();
        return response(null, 204);
    }
}
