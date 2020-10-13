<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Player;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Player::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'player_id' => $faker->numberBetween(100000, 999999),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
