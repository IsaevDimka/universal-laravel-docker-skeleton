<?php


namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Debug service
 * Example use:
 * Before code
 * DebugService::start();
 * After code
 * DebugService::result()
 * Class DebugService
 *
 * @package App\Services
 */
class DebugService
{

    public static $debug_start_microtime = 0;

    /**
     * Process set duration and start query log
     * @return void
     */
    public static function start() : void
    {
        self::$debug_start_microtime = microtime(true);
        DB::enableQueryLog();
    }

    final public static function result($includes = [
        'app_version',
        'latest_release',
        'laravel_version',
        'environment',
        'locale',
        'queryLogs',
        'durations',
    ]) : array
    {
        $durations = [
            'laravel' => format_duration((microtime(true) - LARAVEL_START)),
            'this'    => format_duration((microtime(true) - self::$debug_start_microtime)),
        ];

        $queryExecuted = DB::getQueryLog();
        $queryLogs = [];
        foreach ($queryExecuted as $query)
        {
            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query['query']);
            $bindings = $query['bindings'];
            $realSql = $sqlWithPlaceholders;
            $duration = format_duration($query['time'] / 1000);

            if (count($bindings) > 0) {
                $realSql = vsprintf($sqlWithPlaceholders, $bindings);
            }

            $queryLog = sprintf('[%s] %s', $duration, $realSql);
            array_push($queryLogs, $queryLog);
        }

        $environment = app()->environment();
        $locale = app()->getLocale();
        $laravel_version = app()->version();
        $version = new \PragmaRX\Version\Package\Version();
        $app_version = $version->format();
        $latest_release = \Carbon\Carbon::create($version->format('timestamp-datetime'))->toDateTimeString();

        return compact($includes);
    }
}
