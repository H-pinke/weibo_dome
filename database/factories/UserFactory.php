<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $data_time = $faker->date() . ' ' . $faker->time();
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'activation' => true,
        'password' => '$2y$10$dGWsojzSHBN9Yd0FlFp.1OFZS6fsqt7pIGDT0o5U6DI.uzAWerX8a', // password  $2y$10$dGWsojzSHBN9Yd0FlFp.1OFZS6fsqt7pIGDT0o5U6DI.uzAWerX8a
        'remember_token' => Str::random(10),
        'created_at' => $data_time,
        'updated_at' => $data_time
    ];
});
