<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ClickhouseException;
use Illuminate\Support\Facades\DB;
use Throwable;
use Tinderbox\Clickhouse\Exceptions\TransportException as TinderboxTransportException;

class ClickhouseService
{
    public const COLUMN_UINT8 = 'UInt8';

    public const COLUMN_UINT16 = 'UInt16';

    public const COLUMN_UINT32 = 'UInt32';

    public const COLUMN_UINT64 = 'UInt64';

    public const COLUMN_INT8 = 'Int8';

    public const COLUMN_INT32 = 'Int32';

    public const COLUMN_INT64 = 'Int64';

    public const COLUMN_STRING = 'String';

    public const COLUMN_DATE = 'Date';

    public const COLUMN_DATETIME = 'DateTime';

    public const COLUMN_UUID = 'UUID';

    public const COLUMN_IPV4 = 'IPv4';

    public const COLUMN_IPV6 = 'IPv6';

    public const CONNECTION = 'clickhouse';

    public static function Nullable(string $columnType)
    {
        return "Nullable({$columnType})";
    }

    public static function instance(): self
    {
        return new static();
    }

    public function builder()
    {
        try {
            $server = new \Tinderbox\Clickhouse\Server(
                env('DB_CLICKHOUSE_HOST'),
                env('DB_CLICKHOUSE_PORT'),
                env('DB_CLICKHOUSE_DATABASE'),
                env('DB_CLICKHOUSE_USERNAME'),
                env('DB_CLICKHOUSE_PASSWORD')
            );
            $serverProvider = (new \Tinderbox\Clickhouse\ServerProvider())->addServer($server);
            $client = new \Tinderbox\Clickhouse\Client($serverProvider);
            return new \Tinderbox\ClickhouseBuilder\Query\Builder($client);
        } catch (TinderboxTransportException | Throwable $e) {
            throw new ClickhouseException($e->getMessage(), $e->getCode());
        }
    }

    public function dropTableIfExists(string $tableName = null)
    {
        try {
            $builder = self::builder();
            throw_if(! $tableName, ClickhouseException::class);
            $builder->dropTableIfExists($tableName);
        } catch (Throwable $e) {
            throw new ClickhouseException($e->getMessage(), $e->getCode());
        }
    }

    public function createTableIfNotExists(string $tableName = null, string $engine = null, array $columns = [])
    {
        $builder = self::builder();
        try {
            throw_if((! $tableName || ! $engine || ! $engine), ClickhouseException::class, 'Too few arguments', 500);
            $builder->createTableIfNotExists($tableName, $engine, $columns);
        } catch (TinderboxTransportException | Throwable $e) {
            throw new ClickhouseException($e->getMessage(), $e->getCode());
        }
    }

    public function insert(string $tableName = null, array $data = [])
    {
        try {
            throw_if((! $tableName || ! $data), ClickhouseException::class, 'Too few arguments');
            $clickhouse = DB::connection(self::CONNECTION)->table($tableName);
            $clickhouse->insert($data);
        } catch (TinderboxTransportException | Throwable $e) {
            throw new ClickhouseException($e->getMessage(), $e->getCode());
        }
    }
}
