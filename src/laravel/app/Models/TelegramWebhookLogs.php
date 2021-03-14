<?php

declare(strict_types=1);

namespace App\Models;

use Bavix\LaravelClickHouse\Database\Eloquent\Model;

class TelegramWebhookLogs extends Model
{
    protected $table = 'telegram_webhook_logs';
}
