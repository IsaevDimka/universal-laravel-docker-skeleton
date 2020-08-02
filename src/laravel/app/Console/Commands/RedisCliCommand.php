<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisCliCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:cli {--command=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Running redis-cli command';

    private const allowedRedisCliCommands = [
        'ping',
        'flushdb',
        'flushall',
        'dbsize',
    ];

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
     * @return mixed
     */
    public function handle()
    {
        $command = $this->option('command');

        if (! in_array($command, self::allowedRedisCliCommands))
        {
            $this->error("Redis-cli command $command is not allowed, use one of ".implode(', ',self::allowedRedisCliCommands));
            return;
        }

        $this->alert("Running redis-cli command: $command");
        $this->runRedisCliCommand($command);
    }

    private function runRedisCliCommand($command)
    {
        try{
            $result = Redis::connection()->command($command);
            $this->comment((string) $result);
        } catch (\Throwable $exception)
        {
            $this->error('Exception');
            $this->error('Message: '.(string) $exception->getMessage());
            $this->error('Code: '.$exception->getCode());
        }
    }
}
