<?php

use Illuminate\Database\Seeder;

class CargoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cargos')->insert([            
            [
                'nome' => 'DESENVOLVEDOR DE SISTEMAS',
                'descricao' => 'DESENVOLVEDOR DE SISTEMAS',       
                'departamento_id' => 1,
                'funcionario_id' => 1
            ],
            [
                'nome' => 'OUTROS',
                'descricao' => 'OUTROS',
                'departamento_id' => 3,
                'funcionario_id' => 1,
            ],
            [
                'nome' => 'INFRAESTRUTURA DE TI',
                'descricao' => 'INFRAESTRUTURA DE TI',
                'departamento_id' => 2,
                'funcionario_id' => 1,
            ]   
        ]);
    }
}