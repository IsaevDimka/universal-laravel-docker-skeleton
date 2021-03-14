<?php

use Illuminate\Database\Seeder;
use App\Models\Area;
use Rap2hpoutre\FastExcel\FastExcel;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('areas.json'));
        $areas = json_decode($json, true);
        foreach ($areas as $area)
        {
            Area::create(['code' => $area['code'], 'name' => $area['name']]);
        }
    }
}
