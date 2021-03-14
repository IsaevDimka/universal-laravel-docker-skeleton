<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Bus\Batch;

class JobService
{
    public static function getBatchLoggerPayload(Batch $batch): array
    {
        return [
            'id' => $batch->id,
            'name' => $batch->name,
            'totalJobs' => $batch->totalJobs,
            'pendingJobs' => $batch->pendingJobs,
            'failedJobs' => $batch->failedJobs,
            'processedJobs' => $batch->processedJobs(),
            'progress' => $batch->progress(),
        ];
    }
}
