<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Install
{
    public function production(Runner $run)
    {
        $run
            ->artisan('key:generate', ['--force' => true])
            ->artisan('jwt:secret', ['--force'=> true])
            ->artisan('migrate', ['--force' => true])
            ->artisan('db:seed', ['--force' => true])
            ->artisan('storage:link')
            ->external('npm', 'install', '--production')
            ->external('npm', 'run', 'production')
            ->artisan('horizon:install')
            ->artisan('telescope:publish')
            ->artisan('gauge:install')
            ->artisan('geoip:update')
            ->external('composer clear')
            ->external('composer build');
    }

    public function local(Runner $run)
    {
        $run
            ->artisan('key:generate', ['--force' => true])
            ->artisan('jwt:secret', ['--force'=> true])
            ->artisan('migrate', ['--force' => true])
            ->artisan('db:seed')
            ->artisan('storage:link')
            ->external('npm', 'install')
            ->external('npm', 'run', 'development')
            ->artisan('horizon:install')
            ->artisan('telescope:publish')
            ->artisan('gauge:install')
            ->artisan('geoip:update')
            ->external('composer clear')
            ->external('composer build');
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
