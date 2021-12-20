<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Notifications\ReactionTopicNotification;
use Illuminate\Http\Request;

class TopicReactionController extends Controller
{
    public function toggle(Request $request, Topic $topic)
    {
        $reaction = $topic->toggleReaction($request->reaction);

        if ($reaction && $topic->user_id !== $reaction->user_id) {
            $user = $topic->user->find($reaction->user_id);
            $topic->user->notify(new ReactionTopicNotification($topic, $user, $reaction->type));
        }

        return $reaction;
    }
}
