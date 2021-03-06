<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            # Laravel IDE helper
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
//            # Telescope
//            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
//            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        User::observe(UserObserver::class);

        //        \URL::forceScheme('https');
        Schema::defaultStringLength(256);

        if (file_exists(config_path('settings.json'))) {
            $settings = \json_decode(file_get_contents(config_path('settings.json')), true);
            config([
                'settings' => $settings,
            ]);
        }
    }
}
