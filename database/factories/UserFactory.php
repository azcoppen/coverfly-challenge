<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Coverfly\Models\User;
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

    $first = $faker->firstName;
    $last = $faker->lastName;

    return [
        'first_name'    => $first,
        'last_name'     => $last,
        'vanity'        => Str::slug ($first.'-'.$last),
        'email'         => $faker->unique()->safeEmail,
        'password'      => Hash::make ('bundy'),
        'otp_secret'    => Str::random (24),
        'remember_token'=> Str::random(10),
        'created_by'    => 1,


    ];
});
