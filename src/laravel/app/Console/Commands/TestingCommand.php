<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing
                            {--method= : Only check these urls}';

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

//        $this->line("<fg=green>GREEN</fg=green>\t");
//        $this->line("<fg=red>RED</fg=red>\t");
//        $this->line("<fg=yellow>YELLOW</fg=yellow>\t");
//        $this->line("<fg=white>white</fg=white>");

        dd(\Hash::make('isaevdimka'));
        if (method_exists($this, $method = Str::studly($this->option('method') ?? null))) {
            $this->{$method}();
        }else{
            $this->error("Testing method not found!");
            return 2;
        }

        $meta = \App\Services\DebugService::result();
        $this->comment(\json_encode($meta, JSON_PRETTY_PRINT));
    }

    private function notify()
    {
        $channel = 'mail';
        $route = 'isaevdimka@gmail.com';
        \Illuminate\Support\Facades\Notification::route($channel, $route)->notify(new \App\Notifications\MailMessageNotification());
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
