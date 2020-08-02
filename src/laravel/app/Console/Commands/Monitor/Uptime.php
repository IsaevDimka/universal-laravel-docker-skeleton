<?php

namespace App\Console\Commands\Monitor;

use Illuminate\Console\Command;

class Uptime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:uptime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send uptime to telegram';

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
     * @return mixed
     */
    public function handle()
    {
        $data = shell_exec('uptime -p');
        $uptime = trim(str_replace(["up", "\n"], '', $data));

        $result = "Uptime: {$uptime}";
        $this->line($result);
        logger()->channel('telegram')->info($result, ['type' => 'clear']);
    }
}
