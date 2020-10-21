<?php

namespace App\Console\Commands\Clickhouse;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClickhouseStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickhouse:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        try {
            $query = "SELECT table, round(sum(bytes) / 1024/1024/1024, 2) as size_gb
                        FROM system.parts
                        WHERE active
                        GROUP BY table
                        ORDER BY size_gb DESC";

            $stats = DB::connection('clickhouse')->select($query);

            $this->table(['table', 'size_gb'], $stats);
        } catch (\Throwable $exception) {
            $this->error("Message: " . (string)$exception->getMessage());
            $this->error("Code: " . (int)$exception->getCode());
        }
    }
}
