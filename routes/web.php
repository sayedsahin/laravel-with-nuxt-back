<?php

use App\Http\Resources\ActivityResource;
use App\Http\Resources\ReactionResource;
use App\Models\Activity;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Qirolab\Laravel\Reactions\Models\Reaction;


Route::get('/', function () {

    DB::enableQueryLog();
    $topic = Topic::find(22)->reactionSummary();
    dump($topic);

    $queries = DB::getQueryLog();

    dump($queries);


});

Route::get('/morph', function() {
    $r = Reaction::limit(100)->get();
    return ReactionResource::collection($r);
});

Route::get('/activity', function() {
    return $activities = Activity::limit(100)->get();
    return ActivityResource::collection($activities);
});