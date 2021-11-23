<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RepliesResource extends JsonResource
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
            'topic_id' => $this->topic->id,
            'title' => $this->topic->title,
            'body' => $this->body,
            'created_at' => $this->created_at,
            'user' => new UserResource($this->topic->user),
            'category' => new CategoryResource($this->topic->category),
        ];
    }
}
