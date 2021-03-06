<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\TelegramWebhookLogs;
use App\Services\ClickhouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TelegramWebhookController extends Controller
{
    private ClickhouseService $clickhouseService;

    public function __construct(ClickhouseService $clickhouseService)
    {
        $this->clickhouseService = $clickhouseService;
    }

    public function webhook(Request $request)
    {
        \Services\DebugService::start();
        $debug = \Services\DebugService::result();

        $telegram_webhook_logs = [
            'uuid' => (string) Str::uuid(),
            'app_version' => $debug['app_version'],
            'latest_release' => $debug['latest_release'],
            'laravel_version' => $debug['laravel_version'],
            'environment' => $debug['environment'],
            'locale' => $debug['locale'],
            'duration' => $debug['durations']['this'],
            'ip' => (string) $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'host' => (string) $request->getHost(),
            'url' => (string) $request->fullUrl(),
            'raw' => (string) \json_encode($request->toArray(), JSON_UNESCAPED_UNICODE),
            'event_time' => now()->timestamp,
        ];

        $clickhouse = app(ClickhouseService::class)->builder()->table('telegram_webhook_logs')->insert($telegram_webhook_logs);

        return api()->ok('Telegram webhook', [
            'request' => $request->toArray(),
            'debug' => $debug,
            'telegram_webhook_logs' => $telegram_webhook_logs,
        ]);
    }

    public function get(Request $request)
    {
        $items = TelegramWebhookLogs::all()->filter(fn ($i) => $i['raw'] = \json_decode($i['raw']));
        return api()->ok(null, [
            'items' => $items->toArray(),
            'total' => $items->count(),
        ]);
    }
}
