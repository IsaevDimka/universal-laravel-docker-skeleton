<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FakerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faker:run {--method=} {--limit=}';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Services\DebugService::start();

        $method = $this->option('method');
        $limit = $this->option('limit') ?? 10;
        $this->comment("Method: ${method} | Limit: ${limit}");

        switch ($method):
            case 'logsmongo': $this->logsmongo($limit);
        break;
        case 'weights': $this->weights($limit);
        break;
        default: $this->error('method not found');
        break;
        endswitch;

        $meta = \Services\DebugService::result();
        $this->line('Duration: ' . $meta['durations']['this']);
        $this->line(\json_encode($meta, JSON_PRETTY_PRINT));
    }

    private function weights($limit)
    {
        $weights = [
            'node_1' => 66,
            'node_2' => 33,
        ];
        $this->alert(\json_encode($weights));

        $file = storage_path('/logs/random-weights.txt');
        $log = fopen($file, 'a');
        fwrite($log, 'weights: ' . \json_encode($weights) . PHP_EOL);
        fwrite($log, '-------------------------------------------------' . PHP_EOL);
        fclose($log);

        $i = 1;
        while ($i <= $limit) {
            $rand = mt_rand(0, 1000);
            $result = [];
            foreach ($weights as $node => $weight) {
                $realWeight = $weight * 10;
                if ($rand >= 0 && $rand <= $realWeight) {
                    $result[] = [
                        'i' => $i,
                        'rand' => $rand,
                        'node' => $node,
                        'weight' => $weight,
                        'realWeight' => $realWeight,
                    ];
                    $this->line("${i} | ${rand} | ${weight} (${realWeight}) | ${node}");
                    break;
                }
                $rand -= $realWeight;
            }
            $log = fopen($file, 'a');
            fwrite($log, \json_encode($result) . PHP_EOL);
            fclose($log);
            $i++;
        }
    }

    private function logsmongo($limit)
    {
        $x = 1;
        while ($x <= $limit):
            $duration_item_start = microtime(true);

        $data = [
            'one' => Str::random(10),
            'two' => Str::random(20),
            'three' => Str::random(30),
        ];
        $collection = 'unit';
        logger()->channel('mongodb')->debug('test channel mongodb via collection ' . $collection, compact('data', 'collection'));

        $this->comment($x . ' add Logs to channel MongoDB ' . round(microtime(true) - $duration_item_start, 2) . ' sec.');

        $x++;
        endwhile;
    }
}
