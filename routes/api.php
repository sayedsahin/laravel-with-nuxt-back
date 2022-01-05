<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ReplyReactionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TopicReactionController;
use App\Http\Resources\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // $user = $request->user();
    return response()->json(new UserProfileResource($request->user()));
});

// Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'topic'], function() {

    // Topic crud
    Route::get('/', [TopicController::class, 'index']);
    // must be add middleware
    Route::get('/create', [TopicController::class, 'create']);
    Route::get('/{topic}', [TopicController::class, 'show']);
    Route::get('/{topic}/similar', [TopicController::class, 'similarByTag']);
    Route::get('/{topic}/replies', [TopicController::class, 'replies']);
    Route::get('/{topic}/edit', [TopicController::class, 'edit']);


    Route::post('/', [TopicController::class, 'store'])->middleware('auth:sanctum');
    Route::patch('/{topic}', [TopicController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{topic}', [TopicController::class, 'destroy'])->middleware('auth:sanctum');
    // Reply crud
    Route::post('/{topic}/reply', [ReplyController::class, 'store'])->middleware('auth:sanctum');
    
    // Reaction crud
    Route::post('/{topic}/reaction', [TopicReactionController::class, 'toggle'])->middleware('auth:sanctum');

    // Post Group
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

Route::group(['prefix' => 'reply'], function() {
    // Reaction crud
    Route::get('/{reply}', [ReplyController::class, 'show']);
    Route::post('/{reply}/reaction', [ReplyReactionController::class, 'toggle'])->middleware('auth:sanctum');

    Route::patch('{reply}', [ReplyController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('{reply}', [ReplyController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'tags'], function() {
    Route::get('/', [TagController::class, 'index']);
    Route::get('/{tag:name}', [TagController::class, 'show']);
    Route::get('/{tag:name}/topics', [TagController::class, 'topics']);
    Route::get('/{tag:name}/search', [TagController::class, 'search']);
    Route::post('/', [TagController::class, 'store'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'user'], function() {
    Route::get('/{user}', [ProfileController::class, 'user']);

    // Javascript FormData Not Support with axios patch
    Route::post('/', [ProfileController::class, 'update'])->middleware('auth:sanctum');
    Route::patch('/password', [ProfileController::class, 'updatePassword'])->middleware('auth:sanctum');

    Route::get('/{user}/activity', [ProfileController::class, 'activity']);
    Route::get('/{user}/topic', [ProfileController::class, 'topic']);
    Route::get('/{user}/reply', [ProfileController::class, 'reply']);

    Route::get('/{user}/following', [FollowController::class, 'following']);
    Route::get('/{user}/followers', [FollowController::class, 'followers']);

    Route::post('/{user}/following', [FollowController::class, 'store'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'category'], function() {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{category:slug}', [CategoryController::class, 'show']);
    Route::get('/{category:slug}/topics', [CategoryController::class, 'topics']);
    Route::get('/{category:slug}/search', [CategoryController::class, 'search']);
    Route::post('/{category:slug}/reaction', [CategoryController::class, 'reaction'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'search'], function() {
    Route::get('/', [SearchController::class, 'index']);
    Route::get('/live', [SearchController::class, 'live']);
});


Route::group(['prefix' => 'notifications'], function() {
    Route::get('/', [NotificationController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/count', [NotificationController::class, 'notificationCount'])->middleware('auth:sanctum');
    Route::delete('/', [NotificationController::class, 'destroy'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'pins'], function() {
    Route::get('/topics', [PinController::class, 'topics']);
});

Route::get('/following', [FollowController::class, 'topics'])->middleware('auth:sanctum');

// Sanctum
/*if (!app()->routesAreCached() || config('sanctum.routes') === true) {
    Route::group(['prefix' => config('sanctum.prefix', 'sanctum')], function () {
        Route::get('/csrf-cookie', CsrfCookieController::class.'@show')->middleware('web');
    });
}*/


// Create Custom Fortify

if (Features::enabled(Features::resetPasswords())) {
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware(['guest']);
                
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware(['guest']);
}