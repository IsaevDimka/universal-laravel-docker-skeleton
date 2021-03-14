<?php

declare(strict_types=1);

namespace Opcache;

use Illuminate\Support\ServiceProvider;
use Opcache\Commands\ClearCommand;
use Opcache\Commands\CompileCommand;
use Opcache\Commands\ConfigCommand;
use Opcache\Commands\StatusCommand;

class OpcacheServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/opcache.php' => config_path('opcache.php'),
            ], 'config');

            $this->commands([
                ClearCommand::class,
                CompileCommand::class,
                ConfigCommand::class,
                StatusCommand::class,
            ]);

            $this->app->bind(OpcacheInterface::class, function () {
                return new OpcacheService();
            });
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/opcache.php', 'opcache');
    }
}
