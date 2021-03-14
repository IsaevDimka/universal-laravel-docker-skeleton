<?php

declare(strict_types=1);

namespace Logger;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendHttpRequestJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The number of times the job may be attempted
     * Количество попыток выполнения задачи
     * */
    public $tries = 2;

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

    protected string $url;

    protected array $data;

    protected ?string $proxy;

    /**
     * Create a new job instance.
     */
    public function __construct(string $url, array $data, ?string $proxy = null)
    {
        $this->url = $url;
        $this->data = $data;
        $this->proxy = $proxy;
        $this->connection = 'redis';
        $this->queue = 'default';
    }

    public function tags()
    {
        return ['connection:' . $this->connection, 'TelegramHandler'];
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            if ($this->proxy) {
                $client = new Client([
                    'timeout' => $this->timeout,
                    'base_uri' => $this->url,
                ]);
                $client->post('sendMessage', [
                    'query' => $this->data,
                    'proxy' => $this->proxy,
                ]);
            } else {
                Http::timeout($this->timeout)->post($this->url, $this->data)->throw();
            }
        } catch (\Throwable $e) {
            logger()->error(
                'Send logs to Telegram chat via Telegram bot failed: ' . (string) $e->getMessage(),
                [
                    'url' => $this->url,
                    'data' => $this->data,
                ]
            );
        }
    }
}
