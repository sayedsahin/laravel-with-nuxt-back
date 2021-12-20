<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Qirolab\Laravel\Reactions\Contracts\ReactsInterface;
use Qirolab\Laravel\Reactions\Traits\Reacts;

class User extends Authenticatable implements ReactsInterface
{
    use HasApiTokens, HasFactory, Notifiable, Reacts, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'pivot',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function avatar()
    {
        if ($this->profile_photo_path) {
            return asset($this->profile_photo_path);
        }else{
            // return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=ffffff&background=5674b9';
            return 'https://www.gravatar.com/avatar/'.strtolower(md5($this->email)).'?s=80&d=mp';
        }
    }

    public function ownsTopic(Topic $topic)
    {
        return $this->id === $topic->user->id;
    }

    public function ownsReply(Reply $reply)
    {
        return $this->id === $reply->user->id;
    }
    
    public function ownsPost(Post $post)
    {
        return $this->id === $post->user->id;
    }
    public function hasLikedPost(Post $post) {
        return $post->likes->where('user_id', $this->id)->count() === 1;
    }
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
