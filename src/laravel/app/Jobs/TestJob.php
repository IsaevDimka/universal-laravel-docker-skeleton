<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted
     * Количество попыток выполнения задачи
     * */
    public $tries = 1;

    /**
     * Максимальное количество исключений разрешенных до отказа
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * The number of seconds the job can run before timing out
     *
     */
    public $timeout = 120;

    /**
     * Worker Sleep Duration
     * @var int
     */
    public $sleep = 3;

    /**
     * The number of seconds to wait before retrying the job
     * @var int
     */
    public $retryAfter = 3;

    /**
     * The number of seconds to delay the execution of a queued job
     * @var int
     */
    //    public $delay = 2;

    private array $payload;

    /**
     * Create a new job instance.
     *
     * @param array  $payload
     * @param string $queue
     */
    public function __construct(array $payload = [], string $queue = 'default')
    {
        $this->queue    = $queue;
        $this->payload  = $payload;
    }

    public function tags()
    {
        return ['connection:'.$this->connection, 'delay:'.$this->delay];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger()->channel('telegram')->debug('Running TestJob',
            [
                'type'       => 'clear',
                'payload'    => $this->payload,
                'queue'      => $this->queue,
                'connection' => $this->connection,
                'tags'       => $this->tags(),
            ]);
    }
}
