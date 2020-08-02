<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        /**
         * @xample @carbon('Y-m-d')
         */
        Blade::directive('carbon', function ($format) {
            return now()->format($format);
        });

        /**
         * @example: @env('local')
         */
        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });
    }
}
