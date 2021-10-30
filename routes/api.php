<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TopicReactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'topic'], function() {

    // Topic crud
    Route::get('/', [TopicController::class, 'index']);
    Route::get('/{topic}', [TopicController::class, 'show']);
    Route::post('/', [TopicController::class, 'store'])->middleware('auth:sanctum');
    Route::patch('/{topic}', [TopicController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{topic}', [TopicController::class, 'destroy'])->middleware('auth:sanctum');

    // Reaction crud
    Route::post('/{topic}/reaction', [TopicReactionController::class, 'toggle']);

    // Post crud
    Route::group(['prefix' => '/{topic}/posts'], function() {
        Route::get('/{post}', [PostController::class, 'show'])->middleware('auth:sanctum');
        Route::post('/', [PostController::class, 'store'])->middleware('auth:sanctum');
        Route::patch('/{post}', [PostController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{post}', [PostController::class, 'destroy'])->middleware('auth:sanctum');

        // Like Post
        Route::group(['prefix' => '/{post}/likes'], function() {
            Route::post('/', [PostLikeController::class, 'store'])->middleware('auth:sanctum');
        });
    });
    
});



