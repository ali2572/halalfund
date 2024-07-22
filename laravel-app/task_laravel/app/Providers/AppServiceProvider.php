<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use \Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define("access-use", function (User $user,Article $article) {
            return $user->id == $article->user_id;
        });
    }
}
