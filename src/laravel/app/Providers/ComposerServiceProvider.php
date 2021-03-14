<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
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
