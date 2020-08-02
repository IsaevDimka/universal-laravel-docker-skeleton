<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Update
{
    public function production(Runner $run)
    {
        $run
            ->external('composer', 'install', '--no-dev', '--prefer-dist', '--optimize-autoloader')
//            ->external('npm', 'install', '--production')
//            ->external('npm', 'run', 'production')
            ->artisan('config:clear')
            ->artisan('route:cache')
            ->artisan('view:clear')
            ->artisan('event:cache')
            ->artisan('migrate', ['--force' => true])
            ->artisan('cache:clear')
            ->external('composer dump-autoload')
//            ->artisan('horizon:terminate')
            ->external('chown -R www-data:www-data *')
            ->external('chmod 777 -R storage/')
            ->external('chmod 777 -R bootstrap/cache/');
    }

    public function local(Runner $run)
    {
        $run
            ->external('composer', 'install')
//            ->external('npm', 'install')
//            ->external('npm', 'run', 'development')
            ->artisan('config:clear')
            ->artisan('route:cache')
            ->artisan('view:clear')
            ->artisan('event:cache')
            ->artisan('migrate', ['--force' => true])
            ->artisan('cache:clear')
            ->external('composer dump-autoload')
            ->external('chown -R www-data:www-data *')
            ->external('chmod 777 -R storage/')
            ->external('chmod 777 -R bootstrap/cache/');
    }
}
