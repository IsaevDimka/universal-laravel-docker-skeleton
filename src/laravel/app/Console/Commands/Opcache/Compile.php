<?php

namespace App\Console\Commands\Opcache;

use Illuminate\Console\Command;
use App\Facades\Opcache as Opcache;

class Compile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'opcache:compile {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-compile your application code';

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
        $this->line('Compiling scripts...');

        $opcache = Opcache::compile($this->option('force') ?? false);

        if (isset($opcache['message'])) {
            $this->warn($opcache['message']);
            return 1;
        } elseif ($opcache) {
            $this->info(sprintf('%s of %s files compiled', $opcache['compiled_count'], $opcache['total_files_count']));
        } else {
            $this->error('OPcache not configured');

            return 2;
        }
    }
}
