<?php

namespace App\Console\Commands\Monitor;

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
        $totalSpace = disk_total_space(base_path());
        $freeSpace  = disk_free_space(base_path());
        $usedSpace  = $totalSpace - $freeSpace;

        $percentage = round(($usedSpace / $totalSpace) * 100);

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
