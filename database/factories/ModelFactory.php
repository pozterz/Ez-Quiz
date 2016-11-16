<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
    	'student_id' => $faker->numberBetween($min = 5635512001, $max = 5635512999),
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'name' => $faker->name($gender = null),
        'email' => $faker->email,
        'type' => 'student',
    ];
});
