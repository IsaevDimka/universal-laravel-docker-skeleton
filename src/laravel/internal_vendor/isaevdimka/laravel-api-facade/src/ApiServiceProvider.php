<?php

namespace Api;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/apifacade.php' => config_path('apifacade.php'),
            ], 'config');
        }
        $this->app->bind(ApiInterface::class, function () {
            return new ApiResponse();
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/apifacade.php', 'apifacade');
    }
}
