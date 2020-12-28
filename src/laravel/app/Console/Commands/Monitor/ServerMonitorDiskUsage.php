<?php

namespace App\Console\Commands\Monitor;

use App\Services\BackendService;
use Illuminate\Console\Command;

class ServerMonitorDiskUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:diskusage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disk space enough';

    protected BackendService $backendService;

    /**
     * Create a new command instance.
     *
     * @param BackendService $backendService
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
        $percentage = $this->backendService->getDiskUsage();

        $message = "Server monitor disk usage at {$percentage}%";

        $thresholds = config('monitor.diskspace_percentage_threshold', [
            'warning' => 80,
            'fail'    => 90,
        ]);

        if($percentage >= $thresholds['fail']) {
            logger()->channel('telegram')->emergency($message, ['type' => 'clear']);
        }
        if($percentage >= $thresholds['warning']) {
            logger()->channel('telegram')->warning($message, ['type' => 'clear']);
        }

        $this->info($message);
    }
}
