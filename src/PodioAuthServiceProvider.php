<?php

namespace PodioAuth;

use Illuminate\Support\ServiceProvider;
use PodioAuth\Commands\Sync;

class PodioAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';

        /**
         * Publish migrations
         */
        $this->publishes([
            __DIR__ . '/Models' => app_path()
        ]);


        $this->publishes([
            __DIR__ . '/migrations' => database_path() . '/migrations'
        ], 'migrations');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Include PodioAuth controller
         * Contains username-password and app authentications.
         */
        $this->app->make('PodioAuth\Controllers\PodioAuth');


        /**
         * Include the PodioBrowserSession
         */
        $this->app->make('PodioAuth\Controllers\PodioBrowserSession');
        $this->app->make('PodioAuth\Controllers\HookController');


        /**
         * Include Podio repository.
         * Contain modified Podio functions and rate-limit handling.
         */
        $this->app->make('PodioAuth\Repositories\Podio');

        /**
         * Included commands for syncing Configuration data to DB.
         * @command: php artisan sync:api
         */
        $this->commands([
            Sync::class
        ]);
    }
}
