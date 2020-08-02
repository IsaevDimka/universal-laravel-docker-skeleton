<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
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
         * Timestamp validator
         */
        Validator::extend('timestamp', function ($attribute, $value, $parameters) {
            return ((string)(int)$value === $value)
                && ($value <= PHP_INT_MAX)
                && ($value >= ~PHP_INT_MAX);
        });

    }
}
