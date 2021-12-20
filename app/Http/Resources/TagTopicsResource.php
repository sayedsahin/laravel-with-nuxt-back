<?php

namespace App\Http\Resources;

use App\Http\Resources\TopicsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TagTopicsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'topics_count' => $this->topics_count,
            'topics' => TopicsResource::collection($this->topics()->take(10)->latest()->get())
        ];
    }
}
