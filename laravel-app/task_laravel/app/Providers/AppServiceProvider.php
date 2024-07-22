<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Support\ServiceProvider;
use \Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        ServerProvider::class => ApiResponseProvider::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Article::class, ArticlePolicy::class);
    }
}