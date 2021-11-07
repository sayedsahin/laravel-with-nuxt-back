<?php

namespace App\Http\Resources;

use App\Http\Resources\TagResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicEditResource extends JsonResource
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
            'type_id' => $this->type_id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'body' => $this->body,
            'tags' => TagResource::collection($this->tags)->pluck('id')
        ];
    }
}
