<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicReactionController extends Controller
{
    public function toggle(Request $request, Topic $topic)
    {
        $topic->toggleReaction($request->reaction);
    }
}
