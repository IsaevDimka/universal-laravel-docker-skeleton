<?php


namespace App\Services;

use App\Helpers\HelperTime;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    private function setMicrotimeStart()
    {
    }

    final public static function result() : array
    {
        $durations = [
            'laravel' => formatDuration((microtime(true) - LARAVEL_START)),
            'this'    => formatDuration((microtime(true) - self::$debug_start_microtime)),
        ];

        $queryExecuted = DB::getQueryLog();
        $queryLogs = [];
        foreach ($queryExecuted as $query)
        {
            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query['query']);
            $bindings = $query['bindings'];
            $realSql = $sqlWithPlaceholders;
            $duration = formatDuration($query['time'] / 1000);

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

        return compact( 'app_version', 'latest_release', 'laravel_version', 'environment', 'locale', 'queryLogs', 'durations');
    }
}
