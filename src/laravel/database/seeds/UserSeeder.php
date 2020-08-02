<?php

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        User::create([
            'username'          => 'root',
            'first_name'        => 'root',
            'last_name'         => 'root',
            'email'             => 'root@root.com',
            'phone'             => '+84909994816',
            'email_verified_at' => $now,
            'last_visit_at'     => $now,
            'password'          => 'root',
            'is_active'         => true,
            'locale'            => 'en',
            'options'           => null,
        ]);
    }
}
