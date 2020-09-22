<?php

use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(app()->environment('local')) {
//            factory(\App\Models\News::class, 100)->create();
        }
    }
}
