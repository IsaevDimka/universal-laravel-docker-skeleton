<?php

declare(strict_types=1);

namespace App\Console\Commands\Monitor;

use App\Services\BackendService;
use Illuminate\Console\Command;

class AvgCpuUsage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:AvgCpuUsage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Average CPU usage';

    protected BackendService $backendService;

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
     * @return int
     */
    public function handle()
    {
        $percentage = $this->backendService->getCPUUsagePercentage();

        $message = "Average CPU usage at {$percentage}%";

        $thresholds = config('cpu_usage_percentage_threshold', [
            'warning' => 70,
            'fail' => 90,
        ]);

        if ($percentage >= $thresholds['fail']) {
            logger()->channel('telegram')->emergency($message, [
                'type' => 'clear',
            ]);
        }
        if ($percentage >= $thresholds['warning']) {
            logger()->channel('telegram')->warning($message, [
                'type' => 'clear',
            ]);
        }

        $this->info($message);
    }
}
