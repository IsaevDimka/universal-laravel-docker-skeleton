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
        $json = file_get_contents(database_path('timezones.json'));
        $array = json_decode($json, true);
        $collection = collect($array);
        foreach ($collection as $item)
        {
            \App\Models\Timezone::create($item);
        }
    }
}
