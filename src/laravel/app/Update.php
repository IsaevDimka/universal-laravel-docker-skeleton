<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Update
{
    public function production(Runner $run)
    {
        $run
            ->external('php -d memory_limit=-1 /usr/local/bin/composer update')
            ->artisan('down')
            ->external('npm', 'install')
            ->external('npm', 'run', 'production')
            ->artisan('geoip:update')
            ->external('composer clear')
            ->external('composer build')
            ->artisan('up');
    }

    public function local(Runner $run)
    {
        $run
            ->external('php -d memory_limit=-1 /usr/local/bin/composer update')
            ->artisan('down')
            ->external('npm', 'install')
            ->external('npm', 'run', 'development')
            ->artisan('geoip:update')
            ->external('composer clear')
            ->external('composer build')
            ->artisan('up');
    }
}
