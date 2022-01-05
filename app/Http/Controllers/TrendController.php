<?php

namespace App\Http\Controllers;

use App\Http\Resources\TopicsResource;
use App\Models\Trend;
use Illuminate\Http\Request;

class TrendController extends Controller
{
    public function index(Request $request)
    {
        $topics = Trend::with('topic')
            ->orderByDesc('id')
            ->take(10)
            ->skip(($request->get('page') - 1) * 10)
            ->get()
            ->pluck('topic');
        return TopicsResource::collection($topics);
    }
}
