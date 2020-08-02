<?php

namespace App\Logging;

use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractProcessingHandler;

class MongoDBHandler extends AbstractProcessingHandler
{
    protected const DATEFORMAT = 'Y-m-d';
    protected const DEFAULT_COLLECTION_NAME = 'default';

    protected function write(array $record): void
    {
        if (!empty($record)) {
            try {
                $request = request();
                $extra['server'] = $request->server('SERVER_ADDR');
                $extra['ip'] = $request->ip();
                $extra['host'] = $request->getHost();
                $extra['url'] = $request->getPathInfo();
                $extra['headers'] = $request->header();
                $extra['request'] = $request->all();

                $now = now();

                $collection = $now->format(self::DATEFORMAT) . '_';

                $collection .= $record['context']['collection'] ?? self::DEFAULT_COLLECTION_NAME;
                if ($collection) unset($record['context']['collection']);

                $data = [
                    'level'           => $record['level_name'],
                    'env'             => app()->environment(),
                    'app_version'     => (new \PragmaRX\Version\Package\Version())->format(),
                    'laravel_version' => app()->version(),
                    'domain'          => config('app.url'),
                    'app_locale'      => app()->getLocale(),
                    'app_timezone'    => config('app.timezone'),
                    'collection'      => $collection,
                    'user'            => (\auth()->id() ?? null),
                    'extra'           => $extra,
                    'message'         => $record['message'],
                    'context'         => $record['context'],
                    'timestamp'       => $now->timestamp,
                    'created_at'      => $now->toDateTimeString(),
                ];

                DB::connection('mongodb')->collection($collection)->insert($data);
            } catch (\Throwable $e) {
                logger()->error('Logging channel mongo error: ' . (string)$e->getMessage().' | code '.(string) $e->getCode());
            }
        }
    }
}
