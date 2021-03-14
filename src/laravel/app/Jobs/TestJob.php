<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TestJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
    public $backoff = 3;

    /**
     * The number of seconds to delay the execution of a queued job
     * @var int
     */
    //    public $delay = 2;

    private array $payload;

    private bool $failed;

    /**
     * Create a new job instance.
     */
    public function __construct(array $payload = [], bool $failed = false)
    {
        $this->payload = $payload;
        $this->failed = $failed;
    }

    public function tags()
    {
        return ['connection:' . $this->connection, 'delay:' . $this->delay];
    }

    public function retryAfter()
    {
        /**
         * Exponential backward formula
         * Delay versus attempts
         */
        return now()->addSeconds(
            (int) round(((2 ** $this->attempts()) - 1) / 2)
        );
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        logger()->channel('telegram')->debug(
            'Running TestJob...',
            [
                'type' => 'clear',
                'payload' => $this->payload,
                'failed' => $this->failed,
                'queue' => $this->queue,
                'connection' => $this->connection,
                'tags' => $this->tags(),
            ]
        );
        if ($this->failed) {
            throw new \Exception('Job failed!');
        }
    }
}
