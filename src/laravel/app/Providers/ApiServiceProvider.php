<?php

namespace App\Providers;

use App\Contracts\ApiInterface;
use App\Responses\ApiResponse;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register API class.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApiInterface::class, function () {
            return new ApiResponse();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
