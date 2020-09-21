<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Clickhouse;

class CreateClickhouseHitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected string $table_name = 'hits';

    public function up()
    {
        /**
         * For clickhouse
         */
        $engine  = 'MergeTree(EventDate, (UserID, EventTime, EventDate), 8192)';
        $columns = [

            'UserID'         => Clickhouse::COLUMN_UINT64,
            'Domain'         => Clickhouse::COLUMN_STRING,
            'URLScheme'      => Clickhouse::COLUMN_STRING,
            'URL'            => Clickhouse::COLUMN_STRING,
            'Environment'    => Clickhouse::COLUMN_STRING,
            'AppVersion'     => Clickhouse::COLUMN_STRING,
            'LaravelVersion' => Clickhouse::COLUMN_STRING,
            'QueryParams'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'SearchPhrase'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'Duration' => Clickhouse::COLUMN_UINT32,

            'BrowserEngine' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'BrowserName'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'DeviceBrand'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'DeviceModel'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'DeviceType'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'Locale'        => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'TimeZone'      => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'IP'   => Clickhouse::Nullable(Clickhouse::COLUMN_IPV4),
            'IPv6' => Clickhouse::Nullable(Clickhouse::COLUMN_IPV6),

            'IsBot'    => Clickhouse::Nullable(Clickhouse::COLUMN_UINT8),
            'Route'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'IsMobile' => Clickhouse::Nullable(Clickhouse::COLUMN_UINT8),
            'IsAjax'   => Clickhouse::Nullable(Clickhouse::COLUMN_UINT8),

            'LocationCity'      => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'LocationCountry'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'LocationLatitude'  => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'LocationLongitude' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'LocationRegion'    => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'UserAgent' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'Os'        => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'OsVersion' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'Referer' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'UTMSource'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'UTMMedium'   => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'UTMCampaign' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'UTMContent'  => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'UTMTerm'     => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),

            'EventTime' => Clickhouse::COLUMN_DATETIME,
            'EventDate' => 'DEFAULT toDate(EventTime)',
        ];

        Clickhouse::createTableIfNotExists($this->table_name, $engine, $columns);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Clickhouse::dropTableIfExists($this->table_name);
    }
}
