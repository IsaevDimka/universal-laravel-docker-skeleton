<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\ClickhouseService;

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
            'uuid'            => ClickhouseService::COLUMN_UUID,
            'app_version'     => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'latest_release'  => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'laravel_version' => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'environment'     => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'locale'          => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'duration'        => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'ip'              => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'user_agent'      => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'host'            => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'url'             => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'raw'             => ClickhouseService::Nullable(ClickhouseService::COLUMN_STRING),
            'event_time'      => ClickhouseService::COLUMN_DATETIME,
            'event_date'      => 'DEFAULT toDate(event_time)',
        ];
//        app(ClickhouseService::class)->createTableIfNotExists($this->table_name, $engine, $columns);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        app(ClickhouseService::class)->dropTableIfExists($this->table_name);
    }
}
