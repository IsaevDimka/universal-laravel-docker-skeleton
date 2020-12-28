<?php

namespace App\Console\Commands\Monitor;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PgsqlStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:pgsqlStats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get size of db tables';

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
        $sql = "SELECT nspname || '.' || relname AS table,
            pg_size_pretty(pg_total_relation_size(C.oid)) AS total_size
          FROM pg_class C
          LEFT JOIN pg_namespace N ON (N.oid = C.relnamespace)
          WHERE nspname NOT IN ('pg_catalog', 'information_schema')
            AND C.relkind <> 'i'
            AND nspname !~ '^pg_toast'
          ORDER BY pg_total_relation_size(C.oid) DESC";
        $rows = array_map(fn($row) => (array) $row, DB::select($sql));

        $this->table(['table_name', 'size'], $rows);
    }
}
