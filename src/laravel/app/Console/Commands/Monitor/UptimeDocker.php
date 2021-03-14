<?php

declare(strict_types=1);

namespace App\Console\Commands\Monitor;

use App\Services\BackendService;
use Illuminate\Console\Command;

class UptimeDocker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:uptime-docker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uptime docker app container';

    private BackendService $backendService;

    /**
     * Create a new command instance.
     */
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
        $uptime = 'Uptime docker: ' . $this->backendService->uptimeDocker();
        $this->comment($uptime);
        logger()->channel('telegram')->info($uptime, [
            'type' => 'clear',
        ]);
    }
}
