<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing';

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
        \App\Services\DebugService::start();

        $this->line("<fg=green>GREEN</fg=green>\t");
        $this->line("<fg=red>RED</fg=red>\t");
        $this->line("<fg=yellow>YELLOW</fg=yellow>\t");
        $this->line("<fg=white>white</fg=white>");

        $meta = \App\Services\DebugService::result();
        $this->comment(\json_encode($meta, JSON_PRETTY_PRINT));
    }

    private function getNodeByWeights(array $weights = []) : array
    {
        $rand = mt_rand(0, 1000);
        foreach ($weights as $node => $weight) {
            $realWeight = $weight * 10;
            if ($rand >= 0 && $rand <= $realWeight) {
                return [
                    'node'       => $node,
                    'rand'       => $rand,
                    'weight'     => $weight,
                    'realWeight' => $realWeight,
                ];
                break;
            }
            $rand -= $realWeight;
        }
    }
}
