<?php

use Illuminate\Database\Seeder;

class PermissaoDepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissoes')->insert([
            [
                'menu_id' => 14,
                'descricao' => 'CADASTRAR DEPARTAMENTO',
                'identificador' => 'RH_DEPARTAMENTO_CADASTRAR'
            ],
            [
                'menu_id' => 14,
                'descricao' => 'EDITAR DEPARTAMENTO',
                'identificador' => 'RH_DEPARTAMENTO_EDITAR'
            ],
            [
                'menu_id' => 14,
                'descricao' => 'STATUS DEPARTAMENTO',
                'identificador' => 'RH_DEPARTAMENTO_STATUS'
            ],
            [
                'menu_id' => 14,
                'descricao' => 'VISUALIZAR DEPARTAMENTO',
                'identificador' => 'RH_DEPARTAMENTO_VISUALIZAR'
            ]
        ]);

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 8,
                'permissao_id' => 35
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 36
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 37
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 38
            ]
        ]);
    }
}
