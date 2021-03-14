<?php

declare(strict_types=1);

namespace App\Console\Commands\Monitor;

use App\Console\Commands\Traits\OutputResultDebugServiceTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PgsqlStats extends Command
{
    use OutputResultDebugServiceTrait;

    public const available_methods = [
        'tables',
        'drop_indexes',
        'too_much_seq',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:pgsql-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get PostgresSQL table sizes';

    /**
     * Create a new command instance.
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
        \Services\DebugService::start();

        $method = $this->choice('method:', self::available_methods);

        switch ($method) {
            case 'tables':
                $sql = 'SELECT
                        relname as table,
                        pg_size_pretty(pg_total_relation_size(relid)) As size,
                        pg_size_pretty(pg_total_relation_size(relid) - pg_relation_size(relid)) as external_size,
                        pg_size_pretty(pg_indexes_size(relid)) as index_size
                        FROM pg_catalog.pg_statio_user_tables 
                        ORDER BY pg_total_relation_size(relid) DESC;';
            break;
            case 'drop_indexes':
                $sql = "SELECT
                    indexrelid::regclass as index,
                    relid::regclass as table,
                    'DROP INDEX ' || indexrelid::regclass || ';' as drop_statement
                    FROM pg_stat_user_indexes JOIN pg_index USING (indexrelid) WHERE idx_scan = 0 AND indisunique is false;";
            break;
            case 'too_much_seq':
                $sql = "SELECT
                    relname,
                    seq_scan - idx_scan AS too_much_seq,
                    CASE WHEN seq_scan - coalesce(idx_scan, 0) > 0 THEN 'Missing Index?' ELSE 'OK' END,
                    pg_relation_size(relname::regclass) AS rel_size, seq_scan, idx_scan
                    FROM pg_stat_all_tables
                    WHERE schemaname = 'public' AND pg_relation_size(relname::regclass) > 80000
                    ORDER BY too_much_seq DESC;";
            break;
            default:
                $this->error('Method invalid');
                return 1;
        }
        $this->comment('query:' . PHP_EOL . $sql);

        try {
            $query = DB::connection('pgsql')->select(DB::raw($sql));

            if ($method !== 'too_much_seq') {
                $rows = array_map(fn ($row) => array_merge((array) $row, [
                    'count' => DB::table($row->table)->count(),
                ]), $query);
            } else {
                $rows = array_map(fn ($row) => (array) $row, $query);
            }

            if (count($rows)) {
                $this->table(array_keys(array_first($rows)), $rows);
            }
        } catch (\Throwable $exception) {
            $this->error('Message: ' . (string) $exception->getMessage());
            $this->error('Code: ' . (int) $exception->getCode());
        }

        $this->outputDebugResult(\Services\DebugService::result(['durations']));
    }
}
