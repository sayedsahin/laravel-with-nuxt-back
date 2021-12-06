<?php
namespace App\Models;

trait Followable{
    public function follow(User $user)
    {
        return $this->following()->save($user);
    }

    public function unfollow(User $user)
    {
        return $this->following()->detach($user);
    }

    public function toggleFollow(User $user)
    {
        /*if ($this->following($user)) {
            $this->unfollow($user);
        }else{
            $this->follow($user);
        }*/

        $this->following()->toggle($user);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'user_id')->withTimestamps();
    }
    
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_user_id', $user->id)->exists();
    }
}