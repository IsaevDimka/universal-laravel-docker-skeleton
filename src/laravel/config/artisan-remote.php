<?php

declare(strict_types=1);

return [
    'commands' => [
        \Illuminate\Foundation\Console\UpCommand::class,
        \Illuminate\Foundation\Console\DownCommand::class,
        \Illuminate\Cache\Console\ClearCommand::class,
        \App\Console\Commands\Monitor\AvgCpuUsage::class,
        \App\Console\Commands\Monitor\CheckCertificates::class,
        \App\Console\Commands\Monitor\ServerMonitorDiskUsage::class,
        \App\Console\Commands\Monitor\UptimeServer::class,
        \App\Console\Commands\Monitor\UptimeDocker::class,
        \App\Console\Commands\Monitor\PgsqlStats::class,
    ],
    'auth' => [
        env('ARTISAN_REMOTE_API_KEY_DEPLOY') => [
            \Illuminate\Foundation\Console\UpCommand::class,
            \Illuminate\Foundation\Console\DownCommand::class,
            \Illuminate\Cache\Console\ClearCommand::class,
            \App\Console\Commands\Monitor\AvgCpuUsage::class,
            \App\Console\Commands\Monitor\CheckCertificates::class,
            \App\Console\Commands\Monitor\ServerMonitorDiskUsage::class,
            \App\Console\Commands\Monitor\UptimeServer::class,
            \App\Console\Commands\Monitor\UptimeDocker::class,
            \App\Console\Commands\Monitor\PgsqlStats::class,
        ],
        env('ARTISAN_REMOTE_API_KEY_ROOT') => ['*'],
    ],
    'route_prefix' => 'artisan-remote',
];
