<?php

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=RegionSeeder
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('regions.json'));
        $regions = json_decode($json, true);
        foreach ($regions as $region)
        {
            $area = \App\Models\Area::query()->where('name', 'ilike', '%'.$region['name'].'%')->first();
            if ($area) {
                $this->command->getOutput()->writeln("<comment>Success</comment> ".$region['name']." | area: <info>[".$area->code."] ".$area->name."</info>");
                $region['area_id'] = $area->id;
            }else{
                $this->command->getOutput()->writeln("<error>Not found</error> ".$region['name']);
            }
            \App\Models\Region::create($region);
        }
    }
}
