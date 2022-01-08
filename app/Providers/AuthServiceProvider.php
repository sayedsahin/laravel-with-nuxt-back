<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Topic' => 'App\Policies\TopicPolicy',
        'App\Models\Reply' => 'App\Policies\ReplyPolicy',
        'App\Models\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //https://laravelvuespa.com/authentication/laravel-authentication
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return 'https://sayeeds.herokuapp.com/reset-password/'.$token.'?email='.$user->getEmailForPasswordReset();
        });
    }
}
