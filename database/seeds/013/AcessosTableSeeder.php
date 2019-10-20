<?php

use Illuminate\Database\Seeder;

class AcessosTableSeeder extends Seeder
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
                'nome' => 'CONFIGURAÇÕES - CONFIGURAR PERMISSÕES (ACESSO/PERFIL)'
            ],
            [
                'nome' => 'CONFIGURAÇÕES - CONFIGURAR USUÁRIOS'
            ],
            [
                'nome' => 'PESSOAS - ADMINISTRAR FUNCIONÁRIOS'
            ],
            [
                'nome' => 'CONFIGURAÇÕES - CONFIGURAR PARÂMETROS'
            ],
            [
                'nome' => 'ACESSO BÁSICO'
            ],
            [
                'nome' => 'CONFIGURAÇÕES - CONFIGURAR POLÍTICA DE SENHAS'
            ],
            [
                'nome' => 'LOGS - VISUALIZAR LOG DE ACESSOS'
            ]
        ]);
    }
}
