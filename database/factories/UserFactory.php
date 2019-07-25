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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Client::class, function (Faker $faker) {
    return [
        "fname" => $faker->firstName,
        "lname" => $faker->lastName,
        "mname" => $faker->lastName,
        "email" => $faker->email
    ];
});

$factory->define(App\Business::class, function ($faker) {
    return [
        'client_id'                     => '',
        'name'                          => $faker->company,
        'oic'                           => $faker->name,
        'contact'                       => $faker->phoneNumber,
        'street_number'                 => $faker->buildingNumber,
        'route'                         => $faker->streetName,
        'neighborhood'                  => $faker->streetSuffix,
        'locality'                      => $faker->city,
        'administrative_area_level_1'   => $faker->state,
        'country'                       => $faker->country,
        'postal_code'                   => $faker->postcode,
        'business_nature'               => rand(1,18)
    ];
});



$factory->define( \App\Counsel::class, function (Faker $faker) {
    return [
        "email" => $faker->email,
        'lawyer_type' => $faker->jobTitle,
        'lawyer_code' => $faker->phoneNumber,
    ];
});

$factory->define(\App\Profile::class, function (Faker $faker) use ($factory) {
    return [
        "firstname" => $faker->firstName,
        "lastname" => $faker->lastName,
        'counsel_id' => factory(\App\Counsel::class)->create()->id
    ];
});

$factory->define(\App\ContactInfo::class, function (Faker $faker) use ($factory) {
    return [
        'type' => 'present_address',
        'description' => $faker->address,
        'counsel_id' => factory(\App\Counsel::class)->create()->id
    ];
});