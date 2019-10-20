<?php

use Illuminate\Database\Seeder;

class DepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departamentos')->insert([
            [
                'area_id' => 1,
                'nome' => 'DESENVOLVIMENTO DE SISTEMAS',
                'descricao' => 'DESENVOLVIMENTO DE SISTEMAS',
                'funcionario_id' => 1,
                'ticket' => false
            ],
            [
                'area_id' => 1,
                'nome' => 'INFRAESTRUTURA DE TI',
                'descricao' => 'INFRAESTRUTURA DE TI',
                'funcionario_id' => 1,
                'ticket' => true
            ],
            [
            	'area_id' => 1,
                'nome' => 'OUTROS',
                'descricao' => 'OUTROS',
                'funcionario_id' => 1,
                'ticket' => false
            ]
        ]);
    }
} 
