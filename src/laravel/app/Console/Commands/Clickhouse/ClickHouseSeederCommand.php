<?php

namespace App\Console\Commands\Clickhouse;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ClickHouseSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickhouse:seeder {limit?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run seeds from ClickHouse trackings';

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
        $duration_start = microtime(true);

        $limit = $this->argument('limit') ?? 10;

//        $this->seeder($limit);

        $clickhouse = DB::connection('clickhouse')->table('trackings');

        try {
            $server = new \Tinderbox\Clickhouse\Server('swiftcoin-clickhouse', '8123', 'default', 'default', '');
            $serverProvider = (new \Tinderbox\Clickhouse\ServerProvider())->addServer($server);
            $client = new \Tinderbox\Clickhouse\Client($serverProvider);
            $builder = new \Tinderbox\ClickhouseBuilder\Query\Builder($client);

            $tableName = 'trackings';

            $tracking = $builder->table($tableName);

            $tracking->insert([
                'BrowserEngine' => Str::random(10),
                'BrowserName' => Str::random(10),
                'DeviceBrand' => Str::random(10),
                'DeviceModel' => Str::random(10),
                'DeviceType' => Str::random(10),
                'IP' => '0.0.0.0',
                'IsBot' => 0,
                'UserID' => 1,
                'Locale' => 'VN',
                'LocationCity' => 'VN',
                'LocationCountry' => 'Vietnam',
                'LocationLatitude' => '123.123',
                'LocationLongitude' => '123.123',
                'LocationRegion' => 'Vietnam',
                'Os' => 'test',
                'OsVersion' => 'test',
                'Referer' => 'test',
                'utm_source' => 'test',
                'utm_medium' => 'test',
                'utm_campaign' => 'test',
                'utm_content' => 'test',
                'utm_term' => 'test',
                'UserAgent' => 'test',
                'event_time' => time(),
            ]);


            dd(__METHOD__,
                $tracking->get(),
                $tracking->count(),
                $builder->toSql(),
            );

        } catch (\Throwable $exception)
        {
            $this->error("Message: " . (string)$exception->getMessage());
            $this->error("Code: " . (int)$exception->getCode());
        }

        $duration_stop = microtime(true) - $duration_start;
        $this->comment('Duration: '.round($duration_stop, 2).' sec  .');
    }

    private function seeder($limit = 1)
    {
        $clickhouse = DB::connection('clickhouse')->table('trackings');

        $x = 1;
        while ($x <= $limit):
            $this->comment('add tracking '.$x);

            $clickhouse->insert([
                'BrowserEngine' => '',
                'BrowserName' => '',
                'DeviceBrand' => '',
                'DeviceModel' => '',
                'DeviceType' => '',
                'IP' => '',
                'IsBot' => '',
                'UserID' => 1,
                'Locale' => '',
                'LocationCity' => '',
                'LocationCountry' => '',
                'LocationLatitude' => '',
                'LocationLongitude' => '',
                'LocationRegion' => '',
                'Os' => '',
                'OsVersion' => '',
                'Referer' => '',
                'utm_source' => Str::random(10),
                'utm_medium' => '',
                'utm_campaign' => '',
                'utm_content' => '',
                'utm_term' => '',
                'UserAgent' => '',
                'event_time' => time(),
            ]);

            $x++;
        endwhile;

    }
}
