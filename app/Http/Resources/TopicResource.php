<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\ReactionResource;
use App\Http\Resources\ReplyResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $replyid = $request->reply ? $request->reply : 0;
        return [
            'topic' => [
                'id' => $this->id,
                'title' => $this->title,
                'body' => $this->body,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'view' => $this->view_count,
                'user' => new UserResource($this->user),
                'category' => new CategoryResource($this->category),
                'tags' => TagResource::collection($this->tags),
                'reaction' => $this->reactionSummary(),
                'reacted' => auth('sanctum')->user() ? $this->reacted(auth('sanctum')->user()) : null,
                'reply_count' => $this->replies->count(),
            ],

            // 'replies' => ReplyResource::collection($this->replies()->orderByRaw('IF(id = '.$replyid.', 0, 1)')->latest()->paginate(10))->response()->getData(true),
        ];
    }
}
