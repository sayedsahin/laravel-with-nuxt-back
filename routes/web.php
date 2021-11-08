<?php

use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    DB::enableQueryLog();
    $topic = Topic::find(22)->reactionSummary();
    dump($topic);

    $queries = DB::getQueryLog();

    dump($queries);


});