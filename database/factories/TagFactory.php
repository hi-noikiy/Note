<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(\App\Models\Tag::class, function (Faker $faker) {

    $name = $faker->name;

    return [
        'name' => $name,
        'slug' => str_slug($faker->name),
        'message' => $faker->sentence
    ];
});
