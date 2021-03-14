<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PewPewPew extends Command
{
    protected $signature = 'pewpewpew';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Hold up...');
        sleep(1);
        $this->info('Wait a minute...');
        sleep(2);
        $this->info('It\'s a chopper...');
        $this->comment($this->copter());
    }

    public function copter()
    {
        return '
================--+--=================
                 ~|~                        ,-~~-.
         ____/~~~~~~~======-=              :  /~> :
       /\'~~||~| |== == |-- ~-________________/  /
     _/_|__||_| ||_||_||     isaevdimka.com    <
   (-+|    |    |______|     ___-----```````\__\
    `-+._____ ___________ _-~
   ~-________||__________||_____

     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        ';
    }
}
