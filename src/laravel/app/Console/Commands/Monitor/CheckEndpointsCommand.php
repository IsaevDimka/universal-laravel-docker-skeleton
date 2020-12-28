<?php

namespace App\Console\Commands\Monitor;

use App\Models\MonitorEndpoint;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckEndpointsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:checkEndpoints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check endpoints';

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
        $endpoints = MonitorEndpoint::all();
        $output = "";
        $total = 0;
        $good = 0;
        $bad = 0;

        foreach ($endpoints as $endpoint)
        {
            $total++;
            try {
                $status = Http::get($endpoint->url)->status();
            } catch (\Exception $exception) {
                $status = 500;
            }

            if ((int)$status > 204){
                $emoji = "❌";
                $bad++;
            } else {
                $emoji = "✅";
                $good++;
            }

            $endpoint->update(['latest_http_code' => $status]);

            $output .= "app: ".$endpoint->app . PHP_EOL .
                "name: ".$endpoint->name . PHP_EOL .
                "url: ".$endpoint->url . PHP_EOL .
                "status: ". $status . $emoji . PHP_EOL . PHP_EOL;
        }

        $output = "Total: " . $total . "  " . PHP_EOL .
            $good . " ✅  ". PHP_EOL .
            $bad . " ❌  ". PHP_EOL .
            PHP_EOL . $output;

        $this->comment($output);

        logger()->channel('telegram')->info($output, ['type' => 'clear']);
    }
}
