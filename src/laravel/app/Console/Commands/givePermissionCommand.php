<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class givePermissionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'give:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'give Permission to user';

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
     * @return mixed
     */
    public function handle()
    {
        $username = $this->choice('User ?', User::all()->pluck('username', 'id')->toArray());
        $permission = $this->choice('Permission ?', Permission::all()->pluck('name')->toArray());

        if ($this->confirm('Give permission "' . $permission . '"? to "' . $username . '"')) {
            $user = User::where('username', $username)->first();
            $user->givePermissionTo($permission);
            $this->info('User: "' . $username . '" give permission "' . $permission . '"');
            $this->info($user);
        }
    }
}
