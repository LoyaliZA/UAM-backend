<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // 1. Agregamos esta línea aquí

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
        // 2. Le decimos a Laravel que si está en producción, SIEMPRE use https://
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}