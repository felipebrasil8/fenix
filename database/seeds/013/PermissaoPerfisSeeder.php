<?php

use Illuminate\Database\Seeder;

class PermissaoPerfisSeeder extends Seeder
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
                'menu_id' => 4,
                'descricao' => 'CADASTRAR PERFIL',
                'identificador' => 'CONFIGURACAO_PERFIL_CADASTRAR'
            ],
            [
                'menu_id' => 4,
                'descricao' => 'EDITAR PERFIL',
                'identificador' => 'CONFIGURACAO_PERFIL_EDITAR'
            ],
            [
                'menu_id' => 4,
                'descricao' => 'VISUALIZAR PERFIL',
                'identificador' => 'CONFIGURACAO_PERFIL_VISUALIZAR'
            ],
            [
                'menu_id' => 4,
                'descricao' => 'STATUS PERFIL',
                'identificador' => 'CONFIGURACAO_PERFIL_STATUS'
            ],
            [
                'menu_id' => 4,
                'descricao' => 'COPIAR PERFIL',
                'identificador' => 'CONFIGURACAO_PERFIL_COPIAR'
            ]
          
        ]);

         DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 1,
                'permissao_id' => 6
            ],
            [
                'acesso_id' => 1,
                'permissao_id' => 7
            ],
            [
                'acesso_id' => 1,
                'permissao_id' => 8
            ],
            [
                'acesso_id' => 1,
                'permissao_id' => 9
            ],
            [
                'acesso_id' => 1,
                'permissao_id' => 10
            ]
        ]);
    }
}
