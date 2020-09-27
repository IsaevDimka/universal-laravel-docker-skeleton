<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SocialiteServiceProvider extends ServiceProvider
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
        $this->bootTelegramSocialite();
    }

    private function bootTelegramSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'telegram',
            function ($app) use ($socialite) {
                $config = $app['config']['services.telegram'];
                return $socialite->buildProvider(\App\Providers\Socialite\TelegramServiceProvider::class, $config);
            }
        );
    }
}
