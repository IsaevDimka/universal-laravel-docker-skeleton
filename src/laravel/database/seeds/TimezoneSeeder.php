<?php

use Illuminate\Database\Seeder;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timezones = $this->timezones();
        foreach ($timezones as $timezone)
        {
            \App\Models\Timezone::create($timezone);
        }
    }

    private function timezones(){
        $zones_array = [];
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['name'] = $zone;
            $zones_array[$key]['offset'] = (int)((int)date('O', $timestamp)) / 100;
            $zones_array[$key]['diff'] = date('P', $timestamp);
        }
        usort($zones_array, function ($a, $b) {
            return ($a['offset'] - $b['offset']);
        });

        return $zones_array;
    }
}
