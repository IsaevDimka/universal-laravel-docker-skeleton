<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Install
{
    public function production(Runner $run)
    {
        $run
//            ->external('composer', 'install', '--no-dev', '--prefer-dist', '--optimize-autoloader')
            ->artisan('key:generate', ['--force' => true])
            ->artisan('jwt:secret', ['--force'=> true])
            ->artisan('migrate', ['--force' => true])
            ->artisan('db:seed', ['--force' => true])
            ->artisan('storage:link')
            ->external('npm', 'install', '--production')
            ->external('npm', 'run', 'production')
            ->artisan('horizon:install')
            ->artisan('geoip:update')
            ->artisan('route:cache')
            ->artisan('config:cache')
            ->artisan('event:cache');
    }

    public function local(Runner $run)
    {
        /**
         * composer install
         * php artisan key:generate --force
         * php artisan jwt:secret --force
         * php artisan storage:link
         * npm install
         * npm run dev
         * php artisan horizon:install
         * php artisan geoip:update
         */
        $run
            ->external('composer', 'install')
            ->artisan('key:generate', ['--force' => true])
            ->artisan('jwt:secret', ['--force'=> true])
            ->artisan('migrate', ['--force' => true])
            ->artisan('db:seed')
            ->artisan('storage:link')
            ->external('npm', 'install')
            ->external('npm', 'run', 'development')
            ->artisan('horizon:install')
            ->artisan('geoip:update')
            ->artisan('route:cache')
            ->artisan('config:cache')
            ->artisan('event:cache');
    }

    public function productionRoot(Runner $run)
    {
        $run
//            ->dispatch(new MakeQueueSupervisorConfig)
//            ->dispatch(new MakeSocketSupervisorConfig)
            ->external('supervisorctl', 'reread')
            ->external('supervisorctl', 'update');
    }
}
