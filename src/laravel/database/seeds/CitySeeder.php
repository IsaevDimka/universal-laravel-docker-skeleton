<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('cities.json'));
        $cities = json_decode($json, true);

        foreach ($cities as $city)
        {
            $region = \App\Models\Region::where('name', 'ilike', '%'.$city['region_name'].'%')->first();
            $city['region_id'] = $region->id;
            $city['type'] = $city['city_type'];
            $city['name'] = $city['city'];
            unset($city['city_type']);
            unset($city['city']);
            $city['name_with_type'] = $city['type'].' '.$city['name'];
            \App\Models\City::create($city);
        }
    }
}
