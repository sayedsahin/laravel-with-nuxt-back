<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\ReactionResource;
use App\Http\Resources\ReplyResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Support\Facades\DB;

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
        return [
            'topic' => [
                'id' => $this->id,
                'title' => $this->title,
                'body' => $this->body,
                'created_at' => $this->created_at->diffForHumans(),
                'updated_at' => $this->updated_at->diffForHumans(),
                'user' => new UserResource($this->user),
                'category' => new CategoryResource($this->category),
                'tags' => TagResource::collection($this->tags),
                'view' => $this->view_count,
                'reaction' => $this->reactionSummary(),
                'reacted' => auth('sanctum')->user() ? $this->reacted(auth('sanctum')->user()) : null,
                'reply_count' => $this->replies->count(),
            ],

            'replies' => ReplyResource::collection($this->replies()->latest()->paginate(10))->response()->getData(true),


            // 'query_log' => $queries = DB::getQueryLog(),
        ];
    }
}
