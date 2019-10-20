<?php

use Illuminate\Database\Seeder;

class AcessoSprint13TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acessos')->insert([
            [
                'nome' => 'PESSOAS - GERENCIAR CARGOS, DEPARTAMENTOS E ÁREAS'
            ],
            [
                'nome' => 'CONFIGURAÇÕES - CONFIGURAR TICKETS'
            ],
            [
                'nome' => 'TICKETS - TRATAR TICKETS DO DEPARTAMENTO'
            ],
            [
                'nome' => 'TICKETS - VISUALIZAR TODOS OS TICKETS'
            ],
            [
                'nome' => 'TICKETS - GERENCIAR TICKETS DO DEPARTAMENTO'
            ],
            [
                'nome' => 'TICKETS - EDITAR INFORMAÇÕES DOS TICKETS'
            ]
        ]);
    }
}
