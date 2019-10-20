<?php

use Illuminate\Database\Seeder;

class PermissaoSistemaSeeder extends Seeder
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
                'menu_id' => 8,
                'descricao' => 'CADASTRAR PARÂMETRO',
                'identificador' => 'CONFIGURACAO_SISTEMA_PARAMETRO_CADASTRAR'
            ],
            [
                'menu_id' => 8,
                'descricao' => 'EDITAR PARÂMETRO',
                'identificador' => 'CONFIGURACAO_SISTEMA_PARAMETRO_EDITAR'
            ],
            [
                'menu_id' => 8,
                'descricao' => 'VISUALIZAR PARÂMETRO',
                'identificador' => 'CONFIGURACAO_SISTEMA_PARAMETRO_VISUALIZAR'
            ]
        ]);

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 4,
                'permissao_id' => 17
            ],
            [
                'acesso_id' => 4,
                'permissao_id' => 18
            ],
            [
                'acesso_id' => 4,
                'permissao_id' => 19
            ]
        ]);
    }
}

