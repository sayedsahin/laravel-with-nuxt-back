<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowResource extends JsonResource
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
            'followers' => $this->followers()->count(),
            'following' => $this->following()->count(),
            'isFollowing' => auth('sanctum')->user() ? auth('sanctum')->user()->isFollowing($this->find($this->id)) : null,
        ];
    }
}