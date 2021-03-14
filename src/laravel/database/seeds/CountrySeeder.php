<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=CountrySeeder
     *
     * @return void
     */
    public function run()
    {
        $json      = file_get_contents(database_path('countries.json'));
        $countries = json_decode($json, true);

        foreach($countries as $country){
            \App\Models\Country::create([
                'name_common'   => array_get($country, 'name.common'),
                'name_official' => array_get($country, 'name.official'),
                'iso_code'      => $country['cca2'],
                'raw'           => $country,
                'is_active'     => $country['cca2'] === 'RU',
            ]
            );
        }
    }
}
