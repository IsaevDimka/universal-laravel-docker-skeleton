<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=CountrySeeder
     * @return void
     */
    public function run()
    {
//        $countries = LazyCollection::make(function () {
//            $json = file_get_contents(database_path('countries.json'));
//            $countries = json_decode($json, true);
//            foreach ($countries as $country)
//            {
//                yield $country;
//            }
//        });
//        dd($countries->take(1)->toArray());


//        $json = file_get_contents(database_path('countries.json'));
//        $countries_arr = json_decode($json, true);
//        $countries = collect($countries_arr);
//        dd($countries->take(2)->toArray());

    }
}
