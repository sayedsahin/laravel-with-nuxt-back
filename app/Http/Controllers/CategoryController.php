<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoryTopicResource;
use App\Http\Resources\TopicsResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index(Request $request)
    {
        $skip = ($request->get('page') - 1) * 15;
        $categories = Category::take(15)->skip($skip)->get();
        return CategoriesResource::collection($categories);


    }
    public function show(Category $category)
    {
        return new CategoryTopicResource($category);
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
}
