<?php

declare(strict_types=1);

namespace App\Console\Commands\Clickhouse;

use App\Services\ClickhouseService;
use Illuminate\Console\Command;

class ClickhouseQueryTestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickhouse:testing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * select area_code, uik_number, voting_name, voting_address from cik_commissions
    where match(voting_address, '(?i).*город Дмитров.*') and area_code = 50;

    select toMonth(date) as mount, area_code, uik_number, voting_name, voting_address from cik_commissions
    where match(voting_address, '(?i).*город Электросталь.*') and area_code = 50;
     */
    public function handle()
    {
        $table_name = $this->ask('table name:');
        $clickhouse = app(ClickhouseService::class)->builder();

        $selects = $this->ask('selects:', null);
        $selectsRaw = $selects ? explode(',', str_replace(' ', '', $selects)) : ['area_code', 'uik_number', 'voting_name', 'voting_address'];

        $whereRaw = $this->ask('where sql:');
        try {
            $query = $clickhouse->from($table_name)->select($selectsRaw);
            if (! empty($whereRaw)) {
                $query->whereRaw((string) trim($whereRaw));
            } else {
                $searchString = $this->ask('searchString', 'город Электросталь');
                $area_code = $this->ask('area_code', 50);
                $query->where('area_code', '=', $area_code)
                    ->where('is_found', '=', 1)
                    ->whereRaw("match(voting_address, '(?i).*" . (string) trim($searchString) . ".*')");
            }
            $start_microtime = microtime(true);

            $queryResult = $query->get();

            $this->comment('SQL: ' . $query->toSql());
            $this->comment('Count: ' . $queryResult->count());
            $statistic = [
                'rows' => $queryResult->getStatistic()->getRows(),
                'bytes' => $queryResult->getStatistic()->getBytes(),
                'time' => $queryResult->getStatistic()->getTime(),
                'rowsBeforeLimitAtLeast' => $queryResult->getStatistic()->getRowsBeforeLimitAtLeast(),
            ];
            $this->comment(json_encode(compact('statistic'), JSON_PRETTY_PRINT));
            $this->alert('Result:');

            $rows = $queryResult->getRows();
            $this->table($selects, $rows);

            $this->comment(PHP_EOL . 'Duration: ' . format_duration((microtime(true) - $start_microtime)));
        } catch (\Tinderbox\Clickhouse\Exceptions\TransportException $exception) {
            $this->error((string) $exception->getMessage() . ' | ' . (int) $exception->getCode());
        }
    }
}
