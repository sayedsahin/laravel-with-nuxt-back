<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\FollowResource;
use App\Http\Resources\RepliesResource;
use App\Http\Resources\TopicsResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Image;

class ProfileController extends Controller
{
    public function user(User $user)
    {
        return [
            'data' => new UserProfileResource($user),
            'follow' => new FollowResource($user),
        ];
    }

    public function update(UserUpdateRequest $request)
    {
        $user = auth()->user();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->profile_photo_path) {
            if(file_exists(public_path($user->profile_photo_path))){
                @unlink(public_path($user->profile_photo_path));
            }

            $image = $request->file('profile_photo_path');
            $image_name = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $image_path = 'storage/profile/'.$image_name;
            Image::make($image)->resize(80,80)->save($image_path);
            $user->profile_photo_path = $image_path;
        }
        $user->save();
        return new UserProfileResource($user);
    }

    public function updatePassword(UserPasswordRequest $request)
    {
        $user = auth()->user();
        $hashedPassword =  $user->password;
        if (Hash::check($request->current_password, $hashedPassword)) {
            $user->password = Hash::make($request->password);
            return $user->save();
        }else{
            return response([
                'errors' => [ 'current_password' => 'your current password is invalid !']
            ], 422);
        }
    }

    public function activity(User $user)
    {
        $activity = $user->activities()->latest()->simplePaginate(10);
        return ActivityResource::collection($activity);
    }
    public function topic(User $user, Request $request)
    {
        /* Pagination Logic
         1 - 1 * 10 = 0
         2 - 1 * 10 = 10
         3 - 1 * 10 = 20
        */
        $skip = ($request->get('page') - 1) * 10;
        $topic = $user->topics()
            ->take(10)
            ->skip($skip)
            ->latest()
            ->get();
        return TopicsResource::collection($topic);
    }

    public function reply(User $user, Request $request)
    {
        $replies = $user->replies()
            ->take(10)
            ->skip(($request->get('page') - 1) * 10)
            ->latest()
            ->get();
        return RepliesResource::collection($replies);
    }
}
