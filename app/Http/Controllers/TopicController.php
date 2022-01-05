<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Http\Resources\ReplyReactionDeleteResource;
use App\Http\Resources\ReplyResource;
use App\Http\Resources\TopicEditResource;
use App\Http\Resources\TopicResource;
use App\Http\Resources\TopicsResource;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Qirolab\Laravel\Reactions\Models\Reaction;

class TopicController extends Controller
{
    public function index()
    {
        $topic = Topic::latest()->simplePaginate(10);
        return TopicsResource::collection($topic);
    }
    public function show(Topic $topic)
    {
        // $topic->simplePaginate(10);
        $topic->increment('view_count');
        return new TopicResource($topic);
    }

    public function similarByTag(Topic $topic)
    {
        $tags = $topic->tags->pluck('id');
        $topics = $topic->whereHas('tags', function($q) use ($tags) {
            $q->whereIn('tag_id', $tags);
        })
        ->take(10)
        ->skip((request()->get('page') - 1) * 10)
        ->latest()
        ->get();
        return TopicsResource::collection($topics);
    }

    public function replies(Topic $topic)
    {
        $replyid = request()->reply ? request()->reply : 0;

        if (request()->page || request()->order) {
            if (request()->order === 'old') {
                return ReplyResource::collection($topic->replies()->orderBy('id', 'asc')->paginate(10));
            }elseif(request()->order === 'react') {
                return ReplyResource::collection($topic->replies()->withCount('reactions')->orderBy('reactions_count', 'desc')->paginate(10));
            }else{
                return ReplyResource::collection($topic->replies()->orderBy('id', 'desc')->paginate(10));
            }

        }else{
            $replies = $topic->replies()
            ->orderByRaw('IF(id = '.$replyid.', 0, 1)')
            ->orderBy('id', 'desc')
            ->paginate(10);
            return ReplyResource::collection($replies);
        }
        
    }

    public function create()
    {
        $types = Type::orderBy('id')->get(['id', 'name', 'icon']);
        $categories = Category::orderBy('id')->get(['id', 'name']);
        $tags = Tag::orderBy('id')->get(['id', 'name']);
        return response([
            'types' => $types,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
    public function store(TopicRequest $request)
    {
        $topic = new Topic();

        $topic->type_id = $request->type_id;
        $topic->category_id = $request->category_id;
        $topic->title = $request->title;
        $topic->body = $request->body;
        $topic->user_id = $request->user()->id;

        $topic->save();
        $topic->tags()->attach($request->tags);
        return $topic->id;

        //Alternative
        /*$topic = new Topic;
        $topic->title = $request->title;
        $topic->user()->associate($request->user());

        $post = new Post;
        $post->body = $request->body;
        $post->user()->associate($request->user());
        
        $topic->save();
        $topic->posts()->save($post);*/

        return new TopicResource($topic);
    }

    public function edit(Topic $topic)
    {
        return new TopicEditResource($topic);
    }
    
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->type_id = $request->type_id;
        $topic->category_id = $request->category_id;
        $topic->title = $request->title;
        $topic->body = $request->body;

        $update = $topic->save();
        $topic->tags()->sync($request->tags);
        return $update;

        // Alternative Another project
        /*$this->authorize('update', $topic);
        $topic->title = $request->get('title', $topic->title);
        $topic->save();
        return new TopicResource($topic);*/
    }
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();
        return response(null, 204);
    }
}
