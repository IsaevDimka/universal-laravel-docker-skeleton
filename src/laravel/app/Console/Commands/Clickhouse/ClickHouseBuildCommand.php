<?php

namespace App\Console\Commands\Clickhouse;

use App\Models\Clickhouse;
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

    protected string $table_name = 'telegram_webhook_logs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
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
        Clickhouse::createTableIfNotExists($this->table_name, $engine, $columns);

        $this->comment($this->table_name);
    }
}
