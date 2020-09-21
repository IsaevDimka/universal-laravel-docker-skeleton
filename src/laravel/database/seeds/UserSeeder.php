<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $now         = now();
        $roles       = \App\Models\Role::all()->pluck('name')->toArray();
        $permissions = \App\Models\Permission::all()->pluck('name')->toArray();

        /** @var User $user_1 */
        $user_root = User::create([
            'username'          => 'root',
            'email'             => 'root@laravel.local',
            'phone'             => '+79300000000',
            'email_verified_at' => $now,
            'last_visit_at'     => $now,
            'password'          => 'rootroot',
            'is_active'         => true,
            'locale'            => 'en',
            'options'           => null,
        ]);
        $user_root->syncRoles($roles);
        $user_root->syncPermissions($permissions);

        foreach($roles as $role){
            /** @var User $user */
            $user = User::create([
                'username'          => $role,
                'email'             => $role . '@laravel.local',
                'phone'             => $faker->e164PhoneNumber,
                'email_verified_at' => $now,
                'last_visit_at'     => $now,
                'password'          => $role,
                'is_active'         => true,
                'locale'            => 'en',
                'options'           => null,
            ]);
            $user->assignRole($role);
        }
    }
}
