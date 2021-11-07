<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyReactionController extends Controller
{
    public function toggle(Request $request, Reply $reply)
    {
        return $react = $reply->toggleReaction($request->reaction);
    }
}
