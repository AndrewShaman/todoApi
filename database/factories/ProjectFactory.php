<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'user_id' => factory('App\User')->create()->id,
        'title' => $faker->sentence,
        'description' => $faker->paragraph
    ];
});
