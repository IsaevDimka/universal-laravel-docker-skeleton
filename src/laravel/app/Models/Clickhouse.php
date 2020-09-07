<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Clickhouse
{
    const COLUMN_UINT8    = 'UInt8';
    const COLUMN_UINT16   = 'UInt16';
    const COLUMN_UINT32   = 'UInt32';
    const COLUMN_INT8     = 'Int8';
    const COLUMN_INT32    = 'Int32';
    const COLUMN_INT64    = 'Int64';
    const COLUMN_STRING   = 'String';
    const COLUMN_DATE     = 'Date';
    const COLUMN_DATETIME = 'DateTime';
    const COLUMN_UUID     = 'UUID';

    public const CONNECTION = 'clickhouse';

    public static function Nullable(string $columnType) {
        return "Nullable({$columnType})";
    }

    public static function instance()
    {
        return new static;
    }

    public static function builder()
    {
        $server = new \Tinderbox\Clickhouse\Server(
            env('DB_CLICKHOUSE_HOST'),
            env('DB_CLICKHOUSE_PORT'),
            env('DB_CLICKHOUSE_DATABASE'),
            env('DB_CLICKHOUSE_USERNAME'),
            env('DB_CLICKHOUSE_PASSWORD')
        );
        $serverProvider = (new \Tinderbox\Clickhouse\ServerProvider())->addServer($server);
        $client         = new \Tinderbox\Clickhouse\Client($serverProvider);
        return new \Tinderbox\ClickhouseBuilder\Query\Builder($client);
    }

    public static function dropTableIfExists(string $tableName = '')
    {
        $builder = self::builder();
        $builder->dropTableIfExists($tableName);
    }

    public static function createTableIfNotExists(string $tableName = '', string $engine = '', array $columns = [])
    {
        $builder = self::builder();
        $builder->createTableIfNotExists($tableName, $engine, $columns);
    }

    public static function insert(
        string $tableName = '',
        array $data = []
    ) {
        try{
            $clickhouse = DB::connection(self::CONNECTION)->table($tableName);
            $clickhouse->insert($data);
        } catch(\Tinderbox\Clickhouse\Exceptions\TransportException $exception)
        {
            logger()->error((string) $exception->getMessage());
        }

    }
}
