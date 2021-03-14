<?php

declare(strict_types=1);

namespace App\Console\Commands\Workers;

use App\Models\UserLoginActivity;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UserLoginActivityCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userLoginActivity:cleanup {--offset=30 : Offset in days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete oldest UserLoginActivity items';

    /**
     * Create a new command instance.
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
        $offset = (int) $this->option('offset');
        $this->comment("offset: ${offset} days");

        $past = Carbon::now()->subDays($offset);
        $oldest = UserLoginActivity::where('updated_at', '<=', $past);
        $this->comment('oldest deleted count: ' . $oldest->count());

        $oldest->delete();
    }
}
