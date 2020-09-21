<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Clickhouse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TelegramWebhookController extends Controller
{

    public function __invoke(Request $request)
    {
        \App\Services\DebugService::start();
        $debug = \App\Services\DebugService::result();

        $telegram_webhook_logs = [
            'uuid'            => (string) Str::uuid(),
            'app_version'     => $debug['app_version'],
            'latest_release'  => $debug['latest_release'],
            'laravel_version' => $debug['laravel_version'],
            'environment'     => $debug['environment'],
            'locale'          => $debug['locale'],
            'duration'        => $debug['durations']['this'],
            'ip'              => (string) $request->ip(),
            'user_agent'      => (string) $request->userAgent(),
            'host'            => (string) $request->getHost(),
            'url'             => (string) $request->fullUrl(),
            'raw'             => (string) \json_encode($request->toArray(), JSON_UNESCAPED_UNICODE),
            'event_time'      => now()->timestamp,
        ];
        $clickhouse = Clickhouse::builder()->table('telegram_webhook_logs')->insert($telegram_webhook_logs);
        return api()->ok('Telegram webhook', [
            'request'               => $request->toArray(),
            'debug'                 => $debug,
            'telegram_webhook_logs' => $telegram_webhook_logs,
        ]);
    }
}
