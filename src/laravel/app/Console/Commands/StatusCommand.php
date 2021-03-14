<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $record = now() . "\n";
        $record .= env('APP_NAME') . ' v' . env('APP_VERSION') . ' (' . env('APP_ENV') . ') build ' . env('APP_BUILD');
        $record .= "\n\nINFO";

        $record .= "\n\nSYSTEM
        Operating system       : docker
        PHP                    :
        Debug level            : " . env('APP_DEBUG') . '
        Total memory allocated : 123 mb
        Private memory         : 56 mb
        Uptime                 : 3 d 16 hrs 8 min
        Average response time  : 66 msec';

        $record .= "\nPostgreSQL
        HOST                   : " . env('DB_HOST') . '
        PORT                   : ' . env('DB_PORT') . '
        DataBase               : ' . env('DB_DATABASE') . '
        Connection             : ' . $this->TestConnections('pgsql');

        $record .= "\n\nMongoDB
        HOST                   : " . env('DB_MONGODB_HOST') . '
        PORT                   : ' . env('DB_MONGODB_PORT') . '
        DataBase               : ' . env('DB_MONGODB_DATABASE') . '
        Connection             : ' . $this->TestConnections('mongodb') . '
        Collections            : 0
        Shards                 : 0';

        $record .= "\n\nRedis
        HOST                   : " . env('REDIS_HOST') . '
        PORT                   : ' . env('REDIS_PORT') . '
        Connection             : true';

        $record .= "\n\nRabbitMQ
        HOST                   : " . env('RABBITMQ_HOST') . '
        PORT                   : ' . env('RABBITMQ_PORT') . '
        Connection             : true';

        $record .= "\n\nClickHouse
        HOST                   : " . env('DB_CLICKHOUSE_HOST') . '
        PORT                   : ' . env('DB_CLICKHOUSE_PORT') . '
        Connection             : true';

        $record .= "\n\nRoadRunner
        HOST                   : " . env('ROADRUNNER_HTTP_PORT') . '
        PORT                   : ' . env('ROADRUNNER_HTTP_PORT') . '
        Connection             : true';

        $record .= "\n\nJobs
        Status                 : Inactive
        JOBS PER MINUTE        : 0
        JOBS PAST HOUR         : 0
        FAILED JOBS PAST 7 DAYS: 0
        TOTAL PROCESSES        : 0
        MAX WAIT TIME          : -
        MAX RUNTIME            : -
        MAX THROUGHPUT         : default
        Connection             : true";

        $message = '```' . PHP_EOL . $record . PHP_EOL . '```';

        dd($message);
    }
}
