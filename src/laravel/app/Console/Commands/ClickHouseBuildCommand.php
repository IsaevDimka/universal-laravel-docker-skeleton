<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClickHouseBuildCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickhouse:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clickhouse create database & table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            /**
             * @todo: move to service or model
             */
            $server = new \Tinderbox\Clickhouse\Server(
                env('DB_CLICKHOUSE_HOST'),
                env('DB_CLICKHOUSE_PORT'),
                env('DB_CLICKHOUSE_DATABASE'),
                env('DB_CLICKHOUSE_USERNAME'),
                '');
            $serverProvider = (new \Tinderbox\Clickhouse\ServerProvider())->addServer($server);
            $client = new \Tinderbox\Clickhouse\Client($serverProvider);
            $builder = new \Tinderbox\ClickhouseBuilder\Query\Builder($client);

            $tableName = 'Hits';

            if(1)
            {
                $this->alert("Build table $tableName");

                $builder->createTable($tableName, 'SummingMergeTree(EventDate, (UserID, EventTime, EventDate), 8192)', [
                    'UserID' => 'UInt64',
                    'Domain'         => 'String',
                    'URLScheme'      => 'String',
                    'URL'            => 'String',
                    'Environment'    => 'String',
                    'AppVersion'     => 'String',
                    'LaravelVersion' => 'String',
                    'Params'         => 'Nullable(String)',
                    'SearchPhrase'   => 'Nullable(String)',

                    'Duration' => 'UInt32',

                    'BrowserEngine' => 'Nullable(String)',
                    'BrowserName'   => 'Nullable(String)',
                    'DeviceBrand'   => 'Nullable(String)',
                    'DeviceModel'   => 'Nullable(String)',
                    'DeviceType'    => 'Nullable(String)',
                    'Locale'        => 'Nullable(String)',
                    'TimeZone'      => 'Nullable(String)',

                    'IP'   => 'Nullable(IPv4)',
                    'IPv6' => 'Nullable(IPv6)',

                    'IsBot'    => 'Nullable(UInt8)',
                    'Route'    => 'Nullable(String)',
                    'IsMobile' => 'UInt8',
                    'IsAjax'   => 'UInt8',

                    'LocationCity'      => 'Nullable(String)',
                    'LocationCountry'   => 'Nullable(String)',
                    'LocationLatitude'  => 'Nullable(String)',
                    'LocationLongitude' => 'Nullable(String)',
                    'LocationRegion'    => 'Nullable(String)',

                    'UserAgent' => 'Nullable(String)',
                    'Os'        => 'Nullable(String)',
                    'OsVersion' => 'Nullable(String)',

                    'Referer' => 'Nullable(String)',

                    'UTMSource'   => 'Nullable(String)',
                    'UTMMedium'   => 'Nullable(String)',
                    'UTMCampaign' => 'Nullable(String)',
                    'UTMContent'  => 'Nullable(String)',
                    'UTMTerm'     => 'Nullable(String)',

                    'EventTime' => 'DateTime',
                    'EventDate' => 'DEFAULT toDate(EventTime)',
                ]);
            }

            if(0)
            {
                # drop table
                $builder->dropTableIfExists($tableName);
            }

            /**
             * @TODO: таблица для reports
             */

            $tracking = $builder->table($tableName);

            dd(__METHOD__,
                $tracking->get(),
                $tracking->count(),
                $builder->toSql(),
            );

        } catch (\Throwable $exception)
        {
            $this->error("Message: ".(string) $exception->getMessage());
            $this->error("Code: ".(int) $exception->getCode());
        }
    }
}
