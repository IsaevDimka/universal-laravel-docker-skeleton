<?php

use Illuminate\Database\Seeder;

class TypeOfAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(database_path('types_of_addresses.json'));
        $types_of_addresses = json_decode($json, true);
        foreach ($types_of_addresses as $type_of_address)
        {
            \App\Models\TypeOfAddress::create($type_of_address);
        }
    }
}
