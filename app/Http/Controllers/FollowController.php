<?php

namespace App\Http\Controllers;

use App\Http\Resources\FollowResource;
use App\Http\Resources\FollowingResource;
use App\Http\Resources\TopicsResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    
    public function topics()
    {
        $friends = auth('sanctum')->user()->following()->pluck('id');
        $topics = Topic::whereIn('user_id', $friends)
            ->take(10)
            ->skip((request()->get('page') - 1) * 10)
            ->orderByDesc('id')
            ->get();
        return TopicsResource::collection($topics);
    }

    public function store(User $user)
    {
        auth('sanctum')->user()->toggleFollow($user);
        return new FollowResource($user);
    }

    public function following(User $user)
    {
        $following = $user->following()
            ->take(10)
            ->skip((request()->get('page') - 1) * 10)
            ->latest()
            ->get();
        return FollowingResource::collection($following);
    }

    public function followers(User $user)
    {
        $following = $user->followers()
            ->take(10)
            ->skip((request()->get('page') - 1) * 10)
            ->latest()
            ->get();
        return FollowingResource::collection($following);
    }
}