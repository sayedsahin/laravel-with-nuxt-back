<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\TopicsResource;
use App\Models\Reply;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function index(SearchRequest $request)
    {
        $offset = ($request->get('page', 1) - 1) * 10;
        return DB::select("
            SELECT users.id as user_id, MD5(users.email) as email, users.profile_photo_path, topics.id, topics.title, topics.body, topics.created_at, NULL as reply_id, topics.category_id, categories.name, categories.slug, categories.color
            FROM topics
            INNER JOIN users
            ON topics.user_id = users.id
            INNER JOIN categories
            ON topics.category_id = categories.id
            WHERE title LIKE '%{$request->get('query')}%'
            OR body LIKE '%{$request->get('query')}%'
            UNION SELECT users.id as user_id, MD5(users.email) as email, users.profile_photo_path, topics.id as id, topics.title AS title, replies.body, replies.created_at, replies.id as reply_id, topics.category_id, categories.name, categories.slug, categories.color
            FROM replies
            INNER JOIN users
            ON replies.user_id = users.id
            INNER JOIN topics
            ON replies.topic_id = topics.id
            INNER JOIN categories
            ON topics.category_id = categories.id
            WHERE replies.body LIKE '%{$request->get('query')}%' 
            ORDER BY created_at DESC
            LIMIT 10 OFFSET $offset,
        ");
    }

    public function live(SearchRequest $request)
    {
        $offset = ($request->get('page', 1) - 1) * 10;
        return DB::select("
            SELECT id, title, body, created_at, NULL as reply_id
            FROM topics
            WHERE title LIKE '%{$request->get('query')}%'
            OR body LIKE '%{$request->get('query')}%'
            UNION SELECT topics.id as id, topics.title AS title, replies.body, replies.created_at, replies.id as reply_id
            FROM replies
            INNER JOIN topics
            ON replies.topic_id = topics.id
            WHERE replies.body LIKE '%{$request->get('query')}%' 
            ORDER BY created_at DESC
            LIMIT 10 OFFSET $offset
        ");
    }
}
