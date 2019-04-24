<?php

namespace AgilePixels\Rateable;

use Illuminate\Support\ServiceProvider;

class RateableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/rateable.php' => config_path('rateable.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/rateable.php', 'rateable');
    }
}