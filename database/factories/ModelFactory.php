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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

// Factory for Deposit Model
$factory->define(App\Deposit::class, function(Faker\Generator $faker) {
    return [
        'bank_account_id' => 1,
        'amount' => $faker->randomFloat(2, 4000, 50000),
        'reason' => $faker->word
    ];
});

// Factory for Withdrawal Model
$factory->define(App\Withdrawal::class, function(Faker\Generator $faker) {
    return [
        'bank_account_id' => 1,
        'amount' => $faker->randomFloat(2, 4000, 50000),
        'reason' => $faker->word
    ];
});