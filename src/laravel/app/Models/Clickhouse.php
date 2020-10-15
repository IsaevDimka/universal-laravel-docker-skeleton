<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Throwable;
use App\Exceptions\ClickhouseException;
use \Tinderbox\Clickhouse\Exceptions\TransportException as TinderboxTransportException;

class Clickhouse
{
    const COLUMN_UINT8    = 'UInt8';
    const COLUMN_UINT16   = 'UInt16';
    const COLUMN_UINT32   = 'UInt32';
    const COLUMN_UINT64   = 'UInt64';
    const COLUMN_INT8     = 'Int8';
    const COLUMN_INT32    = 'Int32';
    const COLUMN_INT64    = 'Int64';
    const COLUMN_STRING   = 'String';
    const COLUMN_DATE     = 'Date';
    const COLUMN_DATETIME = 'DateTime';
    const COLUMN_UUID     = 'UUID';
    const COLUMN_IPV4     = 'IPv4';
    const COLUMN_IPV6     = 'IPv6';

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
        try{
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
        } catch(TinderboxTransportException | Throwable $e)
        {
            throw new ClickhouseException($e->getMessage(), $e->getCode());
        }
    }

    public static function dropTableIfExists(string $tableName = null)
    {
        try{
            $builder = self::builder();
            throw_if(!$tableName, ClickhouseException::class);
            $builder->dropTableIfExists($tableName);
        }catch(Throwable $e)
        {
            throw new ClickhouseException($e->getMessage(), $e->getCode());
        }
    }

    public static function createTableIfNotExists(string $tableName = null, string $engine = null, array $columns = [])
    {
        $builder = self::builder();
        try{
            throw_if((!$tableName || !$engine || !$engine), ClickhouseException::class, 'Too few arguments', 500);
            $builder->createTableIfNotExists($tableName, $engine, $columns);
        }catch(TinderboxTransportException | Throwable $e)
        {
            throw new ClickhouseException($e->getMessage(), $e->getCode());
        }
    }

    public static function insert(string $tableName = null, array $data = [])
    {
        try{
            throw_if((!$tableName || !$data), ClickhouseException::class, 'Too few arguments');
            $clickhouse = DB::connection(self::CONNECTION)->table($tableName);
            $clickhouse->insert($data);
        } catch(TinderboxTransportException | Throwable $e)
        {
            throw new ClickhouseException($e->getMessage(), $e->getCode());
        }
    }
}
