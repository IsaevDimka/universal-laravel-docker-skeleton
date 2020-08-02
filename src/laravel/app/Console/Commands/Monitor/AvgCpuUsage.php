<?php

namespace App\Console\Commands\Monitor;

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
        $percentage = round($this->getCPUUsagePercentage(), 2);

        $message = "Average CPU usage at {$percentage}%";

        $thresholds = config('cpu_usage_percentage_threshold', [
            'warning' => 70,
            'fail'    => 90,
        ]);

        if($percentage >= $thresholds['fail']) {
            logger()
                ->channel('telegram')
                ->emergency($message, ['type' => 'clear']);
        }
        if($percentage >= $thresholds['warning']) {
            logger()
                ->channel('telegram')
                ->warning($message, ['type' => 'clear']);
        }

        $this->info($message);
    }

    protected function getCPUUsagePercentage()
    {
        $cpu = shell_exec("grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'");

        return (float) $cpu;
    }
}
