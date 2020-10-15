<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

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
        $method = $this->choice('Method?', ['queue', 'batch']);
        $queue = $this->choice('Queue?', ['default', 'rabbitmq']);
        $connection = $this->choice('Connection?', ['redis', 'rabbitmq']);

        $payload = [
            'test' => now()->toDateTimeString(),
        ];

        switch($method)
        {
            case 'queue':
                \App\Jobs\TestJob::dispatch($payload)->onQueue($queue)->onConnection($connection);
            break;
            case 'batch':
                $batch = Bus::batch([
                    new TestJob($payload),
                    new TestJob($payload),
                ])->then(function (Batch $batch) {
                    // All jobs completed successfully...
                    logger()->channel('telegram')->debug("All jobs completed successfully...", [
                        'batch' => $batch->name,
                        'id' => $batch->id,
                    ]);
                })->catch(function (Batch $batch, Throwable $e) {
                    // First batch job failure detected...
                    logger()->channel('telegram')->debug("First batch job failure detected...", [
                        'batch' => $batch->name,
                        'id' => $batch->id,
                    ]);
                })->finally(function (Batch $batch) {
                    // The batch has finished executing...
                    logger()->channel('telegram')->debug("The batch has finished executing", [
                        'batch' => $batch->name,
                        'id' => $batch->id,
                    ]);
                })
                            ->name('Name of bus')
                            ->onQueue($queue)
                            ->onConnection($connection)
                            ->dispatch();

                $this->alert('Batch ID: '.$batch->id);
            break;
            default:
                $this->error('method not supported...');
        }
    }
}
