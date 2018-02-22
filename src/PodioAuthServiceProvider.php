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

//        /**
//         * Publish migrations
//         */
//        $this->publishes([
//            __DIR__ . '/Models' => app_path()
//        ]);
//
//
//        $this->publishes([
//            __DIR__ . '/migrations' => database_path() . '/migrations'
//        ], 'migrations');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Register controllers.
         */
        $this->app->make('PodioAuth\Controllers\PodioAuth');
        $this->app->make('PodioAuth\Controllers\PodioBrowserSession');
        $this->app->make('PodioAuth\Controllers\HookController');


        /**
         * Register repositories.
         * The Podio.php repo include Podio api with rate-limit handling.
         */
        $this->app->make('PodioAuth\Repositories\Podio');

        /**
         * Register console commands.
         * @command: php artisan sync:api
         */
        $this->commands([
            Sync::class
        ]);

        /**
         * Include models without publishing them.
         */
        $this->app->make('PodioAuth\Models\Api');
        $this->app->make('PodioAuth\Models\AppAuth');
        $this->app->make('PodioAuth\Models\PodioHook');
        $this->app->make('PodioAuth\Models\PodioRequest');

    }
}
