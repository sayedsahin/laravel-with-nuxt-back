<?php

namespace App\Http\Resources;

use App\Http\Resources\TopicsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTopicsResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'color' => $this->color,
            'description' => $this->description,
            'count_topic' => $this->topics()->count(),
            'reacted' => auth('sanctum')->user() ? $this->isReactBy(auth('sanctum')->user()) : false,
            'topics' => TopicsResource::collection($this->topics()->take(10)->latest()->get())
        ];
    }
}
