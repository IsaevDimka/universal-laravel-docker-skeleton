<?php

declare(strict_types=1);

namespace App\Console\Commands\Traits;

trait OutputResultDebugServiceTrait
{
    public array $debugServiceResult;

    public function outputDebugResult(array $debugServiceResult = [])
    {
        $this->debugServiceResult = $debugServiceResult;

        $this->line(PHP_EOL);
        if (empty($this->debugServiceResult)) {
            $this->error('Invalid argument printResultDebugService');
        } else {
            if (! empty($this->debugServiceResult['queryLogs'])) {
                foreach ($this->debugServiceResult['queryLogs'] as $log) {
                    $this->comment($log);
                }
            }
            $this->comment('Duration: ' . $this->debugServiceResult['durations']['this']);
        }
    }
}
