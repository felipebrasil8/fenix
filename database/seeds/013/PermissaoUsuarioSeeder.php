<?php

use Illuminate\Database\Seeder;

class PermissaoUsuarioSeeder extends Seeder
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
                'menu_id' => 5,
                'descricao' => 'CADASTRAR USUÁRIO',
                'identificador' => 'CONFIGURACAO_USUARIO_CADASTRAR'
            ],
            [
                'menu_id' => 5,
                'descricao' => 'EDITAR USUÁRIO',
                'identificador' => 'CONFIGURACAO_USUARIO_EDITAR'
            ],
            [
                'menu_id' => 5,
                'descricao' => 'VISUALIZAR USUÁRIO',
                'identificador' => 'CONFIGURACAO_USUARIO_VISUALIZAR'
            ],
            [
                'menu_id' => 5,
                'descricao' => 'STATUS USUÁRIO',
                'identificador' => 'CONFIGURACAO_USUARIO_STATUS'
            ],
             [
                'menu_id' => 5,
                'descricao' => 'SOLICITAR NOVA SENHA',
                'identificador' => 'CONFIGURACAO_USUARIO_SOLICITAR_SENHA'
            ],
            [
                'menu_id' => 5,
                'descricao' => 'ALTERAR SENHA',
                'identificador' => 'CONFIGURACAO_USUARIO_ALTERAR_SENHA'
            ]
        ]);
       DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 2,
                'permissao_id' => 11
            ],
            [
                'acesso_id' => 2,
                'permissao_id' => 12
            ],
            [
                'acesso_id' => 2,
                'permissao_id' => 13
            ],
            [
                'acesso_id' => 2,
                'permissao_id' => 14
            ],
            [
                'acesso_id' => 2,
                'permissao_id' => 15
            ],
            [
                'acesso_id' => 2,
                'permissao_id' => 16
            ]

        ]);
    }
}
