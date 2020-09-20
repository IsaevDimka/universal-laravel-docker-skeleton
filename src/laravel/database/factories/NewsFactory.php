<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\News;
use Faker\Generator as Faker;
use \Illuminate\Support\Str;

$factory->define(News::class, function(Faker $faker) {
    $title = Str::limit($faker->text, 200);
    return [
        'title'     => $title,
        'slug'      => Str::slug($title),
        'content'   => $faker->text(1000),
        'author_id' => 1,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
