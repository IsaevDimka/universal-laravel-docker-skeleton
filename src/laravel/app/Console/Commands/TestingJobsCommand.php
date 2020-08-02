<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestingJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'testing jobs consumer redis(0), rabbitmq(1)';

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
        $queue = $this->choice('Queue?', ['default', 'rabbitmq']);
        $connection = $this->choice('Connection?', ['redis', 'rabbitmq']);

        $payload = [
            'test' => now()->toDateTimeString(),
        ];

        \App\Jobs\TestJob::dispatch($payload, $queue)->onConnection($connection);
    }

}
