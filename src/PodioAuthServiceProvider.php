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
            __DIR__ . '/migrations' => database_path() . '/migrations'
        ], 'migrations');
        /**
         * Publish config file
         */
        $this->publishes([
            __DIR__ . '/config' => config_path(),
        ], 'config');


        $this->publishes([
            __DIR__ . '/Controllers' => app_path() . '/Http/Controllers',
        ], 'config');


        $this->publishes([
            __DIR__ . '/Repositories' => app_path() . '/Modules/Repo',
        ], 'config');
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
//        $this->app->make('PodioAuth\Controllers\PodioAuth');


        /**
         * Include the PodioBrowserSession
         */
//        $this->app->make('PodioAuth\Controllers\PodioBrowserSession');


        /**
         * Include Podio repository.
         * Contain modified Podio functions and rate-limit handling.
         */
//        $this->app->make('PodioAuth\Repositories\Podio');

        /**
         * Included commands for syncing Configuration data to DB.
         * @command: php artisan sync:api
         */
        $this->commands([
            Sync::class
        ]);
    }
}
