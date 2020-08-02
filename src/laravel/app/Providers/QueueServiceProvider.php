<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Redis::enableEvents();

        /**
         * Logger Queue Redis jobs
         */
        Queue::before(function (JobProcessing $event) {
            logger()->channel('mongodb')->info('Job Processing!', ['collection' => 'JobProcessing', 'job' => [
                'connectionName' => $event->connectionName,
                'payload'        => $event->job->payload(),
            ]]);
        });

        Queue::after(function (JobProcessed $event) {
            logger()->channel('mongodb')->info('Job Processed!', ['collection' => 'JobProcessed', 'job' => [
                'connectionName' => $event->connectionName,
                'payload'        => $event->job->payload(),
            ]]);
        });

        Queue::failing(function (JobFailed $event) {
            logger()->channel('mongodb')->error('Job failed!', ['collection' => 'JobFailed', 'job' => [
                'connectionName' => $event->connectionName,
                'payload'        => $event->job->payload(),
                'exception'      => $event->exception,
            ]]);
            logger()->channel('telegram')->error('Job Failed!', ['type' => 'clear', 'job' => [
                'connectionName' => $event->connectionName,
                'payload'        => $event->job->payload(),
                'exception'      => $event->exception,
            ]]);
        });
    }
}
