<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class giveRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'give:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role to user';

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
        $role = $this->choice('Role ?', Role::all()->pluck('name')->toArray());

        if ($this->confirm('Assign role "' . $role . '"? to "' . $username . '"')) {
            /** @var User $user */
            $user = User::where('username', $username)->first();
            $user->assignRole($role);
            $this->info('User: "' . $username . '" assign Role "' . $role . '"');
            $this->info($user);
        }
    }
}
