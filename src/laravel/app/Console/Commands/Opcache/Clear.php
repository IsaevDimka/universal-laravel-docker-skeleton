<?php

namespace App\Console\Commands\Opcache;

use Illuminate\Console\Command;
use App\Facades\Opcache as Opcache;

class Clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opcache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear OPCache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($opcache = Opcache::getStatus()) {
            $this->info('OPcache cleared');
        } else {
            $this->error('OPcache not configured');
            return 2;
        }
    }
}
