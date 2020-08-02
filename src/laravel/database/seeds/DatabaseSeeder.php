<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Manual run seeder:
     * php artisan db:seed --class=UserSeeder
     * @return void
     */
    public function run()
    {
         $this->call([
             RolesAndPermissionsSeeder::class,
             UserSeeder::class,
             LocaleSeeder::class,
             LanguageSeeder::class,
             CurrencySeeder::class,
             TimezoneSeeder::class,
         ]);
    }
}
