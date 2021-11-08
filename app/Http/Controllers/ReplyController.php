<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Topic;


class ReplyController extends Controller
{
    public function show(Reply $reply)
    {
        return $reply;
    }
    public function store(ReplyRequest $request, Topic $topic)
    {
        // My Way
        /*$reply = Reply::create([
            'topic_id' => $topic->id,
            'user_id' => $request->user()->id,
            'body' => $request->body,
        ]);*/

        // Alternative
        $reply = new Reply;
        $reply->body = $request->body;
        $reply->user()->associate($request->user());

        $topic->replies()->save($reply);
        return new ReplyResource($reply);

    }

    public function update(ReplyRequest $request, Reply $reply)
    {
        $this->authorize('isOwns', $reply);
        $reply->body = $request->body;
        return $reply->save();
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('isOwns', $reply);
        $reply->delete();
        return response(null, 204);
    }
}
