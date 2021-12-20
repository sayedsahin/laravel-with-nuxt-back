<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Qirolab\Laravel\Reactions\Models\Reaction;

class TopicsResource extends JsonResource
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
            'title' => $this->title,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->user),
            'category' => new CategoryResource($this->category),
            'tags' => TagResource::collection($this->tags()->limit(2)->get()),
            'view' => $this->view_count,
            // All reply reaction count must be brought
            'reaction_count' =>  $this->reactions()->count(),
            'reply_count' => $this->replies->count(),
            'last_activity' => $this->replies()->latest()->first(['created_at']),
        ];
    }
}
