<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'activity_type' => $this->activity_type,
            'activity_hint' => $this->type,
            'created_at' => $this->created_at,
        ];

        if ($this->activity_type === 'topic') {
            $data['activity'] = [
                'topic_id' => $this->activity->id,
                'topic_title' => $this->activity->title,
                'body' => $this->activity->body,
                'category' => new CategoryResource($this->activity->category),
                'owner' => new UserResource($this->activity->user),
            ];
        }

        if ($this->activity_type === 'reply') {
            $data['activity'] = [
                'topic_id' => $this->activity->topic->id,
                'topic_title' => $this->activity->topic->title,
                'body' => $this->activity->body,
                'category' => new CategoryResource($this->activity->topic->category),
                'owner' => new UserResource($this->activity->user),
            ];
        }

        if ($this->activity_type === 'reply') {
            $data['activity'] = [
                'topic_id' => $this->activity->topic->id,
                'topic_title' => $this->activity->topic->title,
                'body' => $this->activity->body,
                'category' => new CategoryResource($this->activity->topic->category),
                'owner' => new UserResource($this->activity->user),
            ];
        }

        if ($this->activity_type === 'reaction' && $this->activity !== null) {
            if ($this->activity->reactable_type === 'topic') {
                $data['activity'] = [
                'reactable_type' => $this->activity->reactable_type,
                'topic_id' => $this->activity->reactable->id,
                'topic_title' => $this->activity->reactable->title,
                'body' => $this->activity->reactable->body,
                'type' => $this->activity->type,
                'category' => new CategoryResource($this->activity->reactable->category),
                'owner' => new UserResource($this->activity->reactable->user),
                ];
            }
            
        }

        if ($this->activity_type === 'reaction' && $this->activity !== null) {
            if ($this->activity->reactable_type === 'reply') {
                $data['activity'] = [
                'reactable_type' => $this->activity->reactable_type,
                'topic_id' => $this->activity->reactable->topic->id,
                'topic_title' => $this->activity->reactable->topic->title,
                'body' => $this->activity->reactable->body,
                'type' => $this->activity->type,
                'category' => new CategoryResource($this->activity->reactable->topic->category),
                'owner' => new UserResource($this->activity->reactable->user),
                ];
            }
            
        }

        return $data;
    }
}
