<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SocialiteServiceProvider extends ServiceProvider
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
        $this->bootTelegramDriver();
        $this->bootVkontakteDriver();
        $this->bootGitlabDriver();
        $this->bootZaloDriver();
        $this->bootYandexDriver();
    }

    private function bootTelegramDriver()
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

    private function bootVkontakteDriver()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'vkontakte',
            function ($app) use ($socialite) {
                $config = $app['config']['services.vkontakte'];
                return $socialite->buildProvider(\App\Providers\Socialite\VkontakteServiceProvider::class, $config);
            }
        );
    }

    private function bootGitlabDriver()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'gitlab',
            function ($app) use ($socialite) {
                $config = $app['config']['services.gitlab'];
                return $socialite->buildProvider(\App\Providers\Socialite\GitLabServiceProvider::class, $config);
            }
        );
    }

    private function bootZaloDriver()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'zalo',
            function ($app) use ($socialite) {
                $config = $app['config']['services.zalo'];
                return $socialite->buildProvider(\App\Providers\Socialite\ZaloServiceProvider::class, $config);
            }
        );
    }

    private function bootYandexDriver()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'yandex',
            function ($app) use ($socialite) {
                $config = $app['config']['services.yandex'];
                return $socialite->buildProvider(\App\Providers\Socialite\YandexServiceProvider::class, $config);
            }
        );
    }
}
