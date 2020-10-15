<?php

namespace App\Providers;

use App\Contracts\OpcacheInterface;
use App\Services\OpcacheService;
use Illuminate\Support\ServiceProvider;

class OpcacheServiceProvider extends ServiceProvider
{
    /**
     * Register Opcache services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OpcacheInterface::class, function(){
            return new OpcacheService();
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
