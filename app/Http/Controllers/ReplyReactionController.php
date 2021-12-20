<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Notifications\ReactionReplyNotification;
use Illuminate\Http\Request;

class ReplyReactionController extends Controller
{
    public function toggle(Request $request, Reply $reply)
    {
        $reaction = $reply->toggleReaction($request->reaction);

        if ($reaction && $reply->user_id !== $reaction->user_id) {
            $user = $reply->user->find($reaction->user_id);
            $reply->user->notify(new ReactionReplyNotification($reply, $user, $reaction->type));
        }

        return $reaction;
    }
}
