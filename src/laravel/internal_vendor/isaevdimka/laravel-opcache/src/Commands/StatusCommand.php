<?php

namespace Opcache\Commands;

use Illuminate\Console\Command;
use Opcache\OpcacheFacade as Opcache;

class StatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opcache:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show OPcache status';

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
        $opcache = Opcache::getStatus();
        if ($opcache) {
            $this->displayTables($opcache);
        } else {
            $this->error('OPcache not configured');
            return 2;
        }
    }

    /**
     * Display info tables.
     *
     * @param $data
     */
    private function displayTables($data)
    {
        $general = $data;

        foreach (['memory_usage', 'interned_strings_usage', 'opcache_statistics', 'preload_statistics'] as $unset) {
            unset($general[$unset]);
        }

        $this->table([], $this->parseTable($general));

        $this->line(PHP_EOL.'Memory usage:');
        $this->table([], $this->parseTable($data['memory_usage']));

        if (isset($data['opcache_statistics'])) {
            $this->line(PHP_EOL.'Statistics:');
            $this->table([], $this->parseTable($data['opcache_statistics']));
        }

        if (isset($data['interned_strings_usage'])) {
            $this->line(PHP_EOL.'Interned strings usage:');
            $this->table([], $this->parseTable($data['interned_strings_usage']));
        }

        if (isset($data['preload_statistics'])) {
            $this->line(PHP_EOL.'Preload statistics:');
            $this->table([], $this->parseTable($data['preload_statistics']));
        }
    }

    /**
     * Make up the table for console display.
     *
     * @param $input
     *
     * @return array
     */
    private function parseTable($input)
    {
        $input = (array) $input;
        $bytes = ['used_memory', 'free_memory', 'wasted_memory', 'buffer_size'];
        $times = ['start_time', 'last_restart_time'];

        return array_map(function ($key, $value) use ($bytes, $times) {
            if (in_array($key, $bytes)) {
                $value = number_format($value / 1048576, 2).' MB';
            } elseif (in_array($key, $times)) {
                $value = date('Y-m-d H:i:s', $value);
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            return [
                'key' => $key,
                'value' => is_array($value) ? implode(PHP_EOL, $value) : $value,
            ];
        }, array_keys($input), $input);
    }
}
