<?php

use Illuminate\Database\Migrations\Migration;
use \App\Models\Clickhouse;

class TelegramWebhookLogs extends Migration
{
    protected string $table_name = 'telegram_webhook_logs';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * For clickhouse
         */
        $engine  = 'MergeTree(event_date, (uuid), 8192)';
        $columns = [
            'uuid'            => Clickhouse::COLUMN_UUID,
            'app_version'     => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'latest_release'  => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'laravel_version' => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'environment'     => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'locale'          => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'duration'        => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'ip'              => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'user_agent'      => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'host'            => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'url'             => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'raw'             => Clickhouse::Nullable(Clickhouse::COLUMN_STRING),
            'event_time'      => Clickhouse::COLUMN_DATETIME,
            'event_date'      => 'DEFAULT toDate(event_time)',
        ];
//        Clickhouse::createTableIfNotExists($this->table_name, $engine, $columns);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Clickhouse::dropTableIfExists($this->table_name);
    }
}
