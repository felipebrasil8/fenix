<?php

use Illuminate\Database\Seeder;

class ParametrosTipoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parametros_tipo')->insert([
            [
                'nome' =>'TEXTO',
                'ativo' => 'TRUE'
            ],
            [
                'nome' =>'NÚMERO',
                'ativo' => 'TRUE'
            ],
            [
                'nome' =>'BOOLEANO',
                'ativo' => 'TRUE'
            ],
        ]);
    }
}
