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

/**
 * Define os dados de Usuario Fake
 */
$factory->define(App\Usuario::class, function (Faker\Generator $faker) {
	static $password;
    return [

        'nome' => $faker->name,
	    'email' => $faker->safeEmail,
	    'password' => bcrypt(123456),
	    'usuario' => $faker->name,
	    'fk_inclusao' => '1',//recursivo
	    'fk_alteracao' => '1',//recursivo
	    'fk_perfil' => '1',
	    'fk_funcionario' => '1',
	    'ativo' => 'true',
	    'remember_token' => str_random(10),

    ];
});         
            