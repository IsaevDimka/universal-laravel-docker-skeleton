<?php

namespace Opcache\Commands;

use Illuminate\Console\Command;
use Opcache\OpcacheFacade as Opcache;

class ConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opcache:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show your OPcache configuration';

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
        $opcache = Opcache::getConfig();

        if($opcache) {
            $this->line('Version info:');
            $this->table([], $this->parseTable($opcache['version']));

            $this->line(PHP_EOL . 'Configuration info:');
            $this->table([], $this->parseTable($opcache['directives']));
        }else{
            $this->error('OPcache not configured');

            return 2;
        }
    }

    /**
     * Make up the table for console display.
     *
     * @param $input
     *
     * @return array
     */
    protected function parseTable($input)
    {
        $input = (array) $input;

        return array_map(function($key, $value) {
            $bytes = ['opcache.memory_consumption'];

            if(in_array($key, $bytes)) {
                $value = number_format($value / 1048576, 2) . ' MB';
            }elseif(is_bool($value)){
                $value = $value ? 'true' : 'false';
            }

            return [
                'key'   => $key,
                'value' => $value,
            ];
        }, array_keys($input), $input);
    }
}
