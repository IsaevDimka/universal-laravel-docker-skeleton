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
            'email'             => 'root@adstracker.space',
            'phone'             => '+84909994816',
            'email_verified_at' => $now,
            'last_visit_at'     => $now,
            'password'          => bcrypt('root'),
            'is_active'         => true,
            'locale'            => 'en',
            'options'           => null,
        ]);
    }
}
