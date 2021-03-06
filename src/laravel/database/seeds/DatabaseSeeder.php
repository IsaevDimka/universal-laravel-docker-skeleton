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
             LocaleSeeder::class,
             LanguageSeeder::class,
             CurrencySeeder::class,
             TimezoneSeeder::class,
             UserSeeder::class,
             CountrySeeder::class,
             AreaSeeder::class,
             RegionSeeder::class,
             CitySeeder::class,
             TypeOfAddressSeeder::class,
         ]);
    }
}
