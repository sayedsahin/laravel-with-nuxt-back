<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopicsResource;
use App\Models\Pin;

class PinController extends Controller
{
    public function topics()
    {
        $topics = Pin::with('topic')
            ->orderByDesc('id')
            ->limit(3)
            ->get()
            ->pluck('topic');
        return TopicsResource::collection($topics);
    }
}
