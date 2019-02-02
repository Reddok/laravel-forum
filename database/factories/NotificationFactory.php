<?php

use Faker\Generator as Faker;

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

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => \Illuminate\Support\Str::uuid()->toString(),
        'type' => \App\Notifications\ThreadWasUpdated::class,
        'notifiable_type' => \App\User::class,
        'notifiable_id' => function () {
            return auth()->id() ?: create(\App\User::class)->id;
        },
        'data' => ['foo' => 'bar'],
    ];
});
