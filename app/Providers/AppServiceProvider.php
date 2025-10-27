<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Configuration du mapping des relations polymorphes
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'client' => \App\Models\User::class,
            'agency' => \App\Models\User::class,
        ]);
    }
}
