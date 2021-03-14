<?php

declare(strict_types=1);

namespace App\Console\Commands\Monitor;

use App\Services\BackendService;
use Illuminate\Console\Command;

class UptimeServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:uptime-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uptime server';

    /**
     * Create a new command instance.
     */
    private BackendService $backendService;

    public function __construct(BackendService $backendService)
    {
        $this->backendService = $backendService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $uptime = 'Uptime server: ' . $this->backendService->uptimeServer();
        $this->comment($uptime);
        logger()->channel('telegram')->info($uptime, [
            'type' => 'clear',
        ]);
    }
}
