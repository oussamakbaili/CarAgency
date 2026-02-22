<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
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
        // Force HTTPS en production pour éviter le contenu mixte (assets, Vite, liens)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Configuration du mapping des relations polymorphes
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'client' => \App\Models\User::class,
            'agency' => \App\Models\User::class,
        ]);

        // Ensure the public storage symlink exists in production-like environments
        if (!app()->runningInConsole() && !file_exists(public_path('storage'))) {
            try {
                Artisan::call('storage:link');
            } catch (\Exception $e) {
                Log::warning('Unable to create storage symlink: ' . $e->getMessage());
            }
        }
    }
}
