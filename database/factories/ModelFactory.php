<?php

use App\User;
use App\Models\Api\Campaigns;
use App\Models\Api\OptionTypes;
use App\Models\Api\Questions;
use Faker\Generator;

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

$factory->define(User::class, function (Generator $faker) {
    return [
        'firstName' => $faker->firstName,
        'lastName' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'accept_terms' => $faker->randomElement([0, 1]),
        'verificationHash' => User::generateVerificationHash(),
        'isVerified' => $faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});
