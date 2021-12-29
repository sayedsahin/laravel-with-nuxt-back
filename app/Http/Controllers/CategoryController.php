<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoryTopicsResource;
use App\Http\Resources\TopicsResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index(Request $request)
    {
        $skip = ($request->get('page') - 1) * 15;
        $categories = Category::withCount('topics')->take(15)->skip($skip)->get();
        return CategoriesResource::collection($categories);
    }

    public function show(Category $category)
    {
        return new CategoryTopicsResource($category);
    }

    public function topics(Request $request, Category $category)
    {
        $topics = $category->topics()
        ->take(10)
        ->skip(($request->get('page') - 1) * 10)
        ->latest()
        ->get();

        return TopicsResource::collection($topics);
    }

    public function reaction(Category $category)
    {
        return $category->toggleReaction('love');
    }

    public function search(SearchRequest $request, Category $category)
    {
        $topics = $category->topics()
            ->where(function($query){
                $query->where('title', 'LIKE', '%'.request()->get('query').'%')
                   ->orWhere('body', 'LIKE', '%'.request()->get('query').'%');
            })
            ->take(10)
            ->skip((request()->get('page') - 1) * 10)
            ->latest()
            ->get();
        return TopicsResource::collection($topics);
    }
}
