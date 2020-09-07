<?php

namespace App\Console\Commands\Telegram;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class SetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:setWebhook
                            {--webhookUrl=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set webhook for telegram bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected TelegramService $telegramService;

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
        $res = $this->telegramService->setWebhook($this->option('webhookUrl'));
        $this->comment(\json_encode($res, JSON_PRETTY_PRINT));
    }
}
