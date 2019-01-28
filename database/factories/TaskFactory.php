<?php

use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'project_id' => factory('App\Project')->create()->id,
        'description' => $faker->sentence,
        'completed' => false
    ];
});
