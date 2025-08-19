<?php

namespace PragmaRX\Google2FALaravel;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pragmarx.google2fa', function ($app) {
            return $app->make(Google2FA::class);
        });
    }

    public function boot(Router $router)
    {
        /**
         * Config
         * php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider" --tag=config
         * config/google2fa.php
         */
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'google2fa');
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('google2fa.php'),
        ], 'config');

        /**
         * Migrations
         * php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider" --tag=migrations
         * database/migrations/2025_08_19_022548_add_2fa_secret.php
         */
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        /**
         * Middleware: '2fa'
         */
        $router->aliasMiddleware('2fa', \PragmaRX\Google2FALaravel\Middleware::class);

        /**
         * Routes: api.google2fa.qr
         *  /google2fa/qr
         */
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        /**
         * Views
         * php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider" --tag=views
         *  * Inertia.js - resources/js/pages/google2fa/index.tsx
         *  * Blade      - resources/views/google2fa/index.blade.php
         */
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views'),
            __DIR__.'/../resources/js/pages' => resource_path('js/pages'),
        ], 'views');
    }
}
