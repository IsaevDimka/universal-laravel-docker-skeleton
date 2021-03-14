<?php

declare(strict_types=1);

namespace App\Console\Commands\Telegram;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class getWebhookInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:getWebhookInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert('Process set telegram webhook');
        $res = $this->telegramService->getWebhookInfo();
        $this->comment(\json_encode($res, JSON_PRETTY_PRINT));
    }
}
