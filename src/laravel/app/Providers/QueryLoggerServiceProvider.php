<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QueryLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app['config']->get('app.debug_query_logger')) {
            return;
        }

        DB::listen(function (QueryExecuted $query) {
            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

            $bindings = $query->connection->prepareBindings($query->bindings);
            $pdo = $query->connection->getPdo();
            $realSql = $sqlWithPlaceholders;
            $duration = format_duration($query->time / 1000);

            if (count($bindings) > 0) {
                $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
            }
            Log::channel('querylog')->debug(sprintf('[%s] %s | %s: %s', $duration, $realSql, request()->method(), request()->getRequestUri()));
        });
    }
}
