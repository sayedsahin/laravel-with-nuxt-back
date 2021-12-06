<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'level' => $this->level,
            'avatar' => $this->avatar(),
            'following_at' => $this->pivot->created_at,
            'last_activity' => $this->activities()->latest()->first(['created_at']),
            'topics' => $this->topics->count(),
            'replies' => $this->replies->count(),
        ];
    }
}
