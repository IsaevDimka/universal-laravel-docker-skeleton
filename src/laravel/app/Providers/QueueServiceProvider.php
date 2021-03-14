<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        Redis::enableEvents();

        /**
         * Logger Queue Redis jobs
         */
        Queue::before(function (JobProcessing $event) {
            $payload = $event->job->payload();
            logger()->channel('mongodb')->info('Job Processing!', [
                'collection' => 'JobProcessing',
                'job' => [
                    'connectionName' => $event->connectionName,
                    'displayName' => $payload['displayName'],
                    'payload' => $payload,
                ],
            ]);
        });

        Queue::after(function (JobProcessed $event) {
            $payload = $event->job->payload();
            logger()->channel('mongodb')->info('Job Processed!', [
                'collection' => 'JobProcessed',
                'job' => [
                    'connectionName' => $event->connectionName,
                    'displayName' => $payload['displayName'],
                    'payload' => $payload,
                ],
            ]);
        });

        Queue::failing(function (JobFailed $event) {
            $payload = $event->job->payload();
            logger()->channel('mongodb')->error('Job failed!', [
                'collection' => 'JobFailed',
                'job' => [
                    'connectionName' => $event->connectionName,
                    'displayName' => $payload['displayName'],
                    'payload' => $event->job->payload(),
                    'exception' => $event->exception,
                ],
            ]);
            if (Str::is('*SendHttpRequestJob', $payload['displayName'])) {
                logger()->error('Job Failed', [
                    'job' => [
                        'displayName' => $payload['displayName'],
                        'connectionName' => $event->connectionName,
                        'job_id' => $event->job->getJobId(),
                        'payload' => $payload,
                        'exception' => $event->exception,
                    ],
                ]);
            } else {
                logger()->channel('telegram')->error('Job Failed!', [
                    'type' => 'clear',
                    'job' => [
                        'displayName' => $payload['displayName'],
                        'connectionName' => $event->connectionName,
                        'job_id' => $event->job->getJobId(),
                        'payload' => $payload,
                        'exception' => $event->exception,
                    ],
                ]);
            }
        });
    }
}
