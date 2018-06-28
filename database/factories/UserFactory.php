<?php

use Faker\Generator as Faker;

$factory->define( App\User::class, function ( Faker $faker ) {
	return [
		'first_name'      => $faker->firstName,
		'last_name'       => $faker->lastName,
		'user_name'       => $faker->userName,
		'email'           => $faker->unique()->safeEmail,
		'password'        => bcrypt( '123456' ),
		'remember_token'  => str_random( 10 ),
		'dob'             => $faker->date( $format = 'Y-m-d', $max = 'now' ),
		'country_id'      => $faker->numberBetween( 0, 246 ),
		'mobile'          => $faker->phoneNumber,
		'phone'           => $faker->phoneNumber,
		'gender'          => $faker->randomElement( [ 'male', 'female', 'third_gender' ] ),
		'address'         => $faker->address,
		'website'         => $faker->domainName,
		'photo'           => $faker->imageUrl( 800, 600 ),
		'picture'         => $faker->imageUrl( 800, 600 ),
		'education'       => $faker->company,
		'education_title' => $faker->jobTitle,
		'inspiration'     => $faker->paragraph,
		'exhibition'      => $faker->paragraph,
		'technique'       => $faker->sentence,
		'user_type'       => $faker->randomElement( [ 'user', 'admin', 'artist', 'gallery' ] ),
		'active_status'   => $faker->randomElement( [ 0, 1, 2 ] ),
	];
} );