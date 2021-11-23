<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReactionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => $this->reactable_type,
            'body' => $this->reactable?->body
        ];
    }
}
