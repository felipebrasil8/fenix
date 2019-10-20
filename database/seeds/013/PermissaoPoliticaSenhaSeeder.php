<?php

use Illuminate\Database\Seeder;

class PermissaoPoliticaSenhaSeeder extends Seeder
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
                'menu_id' => 12,
                'descricao' => 'VISUALIZAR POLÍTICA DE SENHA',
                'identificador' => 'CONFIGURACAO_SISTEMA_POLITICA_VISUALIZAR'
            ],
            [
                'menu_id' => 12,
                'descricao' => 'EDITAR POLÍTICA DE SENHA',
                'identificador' => 'CONFIGURACAO_SISTEMA_POLITICA_EDITAR'
            ]
        ]);

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 6,
                'permissao_id' => 29
            ],
            [
                'acesso_id' => 6,
                'permissao_id' => 30
            ]
        ]);

        DB::table('politica_senhas')->insert([
            [
                'usuario_inclusao_id' => 1
            ]
        ]);
    }
}
