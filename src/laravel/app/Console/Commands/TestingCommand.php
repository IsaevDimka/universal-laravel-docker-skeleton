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
                            {--f= : Functions}
                            ';

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

        if(method_exists($this, $f = Str::studly($this->option('f') ?? null))) {
            $this->alert('Running function: ' . $f);
            $this->{$f}();
        }else{
            $this->error("Testing method not found!");
            return 2;
        }

        $meta = \App\Services\DebugService::result([
            'durations',
            'queryLogs'
        ]);
        $this->comment(\json_encode($meta, JSON_PRETTY_PRINT));
    }

    private function settingsJson()
    {
        dd(config('settings.key'));
    }

    private function arrayValuesToInt()
    {
        $array = [
            "1", 2, "3"
        ];
        dd($array, array_values_to_int($array));
    }

    private function arrayValuesToString()
    {
        $array = [
            "1", 2, "3"
        ];
        dd($array, array_values_to_string($array));
    }

    private function filewrite()
    {
        $result = [];
        $result = \json_encode($result, JSON_PRETTY_PRINT);

        $file = storage_path('/logs/' . __FUNCTION__ . '.json');
        $log  = fopen($file, 'a');
        fwrite($log, $result);
        fclose($log);
    }

    private function progressBar()
    {
        $count_items = 10;

        $duration_start = microtime(true);

        $progressBar = $this->output->createProgressBar($count_items);
        $progressBar->setFormat('debug');
        $progressBar->getProgressPercent();
        $progressBar->display();
        $progressBar->start();

        $moon = [];
        for($i = 1; $i <= $count_items; $i++){
            sleep(1);
            $moon[] = $i;
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->info("\n");
        $this->info('Count elements in array: ' . count($moon));
        $this->info('Duration: ' . format_duration(microtime(true) - $duration_start));
    }

    private function notify()
    {
        $channel = 'mail';
        $route   = 'isaevdimka@gmail.com';
        $data    = [
            'subject'      => $subject ?? 'Спасибо за регистрацию',
            'replyTo'      => $replyTo ?? null,
            'line_1'       => $line_1 ?? 'Ваша логин: ',
            'line_2'       => $line_2 ?? 'Ваш пароль: ' . $route,
            'action_label' => $action_label ?? 'Перейти на сайт',
            'action_url'   => $action_url ?? url()->to('/'),
            'line_3'       => $line_3 ?? null,
        ];
        \Illuminate\Support\Facades\Notification::route($channel, $route)
                                                ->notify(new \App\Notifications\MailMessageNotification());
    }

    private function getNodeByWeights(array $weights = []): array
    {
        $rand = mt_rand(0, 1000);
        foreach($weights as $node => $weight){
            $realWeight = $weight * 10;
            if($rand >= 0 && $rand <= $realWeight) {
                return [
                    'node'       => $node,
                    'rand'       => $rand,
                    'weight'     => $weight,
                    'realWeight' => $realWeight,
                ];
            }
            $rand -= $realWeight;
        }
    }

    private function sendMail()
    {
        $this->alert(__FUNCTION__);
        try{
            $recipient = 'isaevdimka@gmail.com';
            \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\WelcomeMail());
        } catch(\Throwable $e){
            dd($e->getMessage());
        }
    }

    private function testLogToMongoDB()
    {
        logger()->channel('mongodb')->error('Test message', [
            'collection' => 'TestCollection',
            'data'       => ['key' => 'value'],
        ]);
    }

    private function testLogToTelegram()
    {
        $data = [
            'key' => 'value',
        ];
        $type = null; # without param type or clear or message
        logger()->channel('telegram')->error("test message", [
            'type' => $type,
            'data' => $data,
        ]);
    }
}
