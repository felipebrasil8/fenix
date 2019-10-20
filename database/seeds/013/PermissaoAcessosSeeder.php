<?php

use Illuminate\Database\Seeder;

class PermissaoAcessosSeeder extends Seeder
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
                'menu_id' => 3,
                'descricao' => 'CADASTRAR ACESSO',
                'identificador' => 'CONFIGURACAO_ACESSO_CADASTRAR'
            ],
            [
                'menu_id' => 3,
                'descricao' => 'EDITAR ACESSO',
                'identificador' => 'CONFIGURACAO_ACESSO_EDITAR'
            ],
            [
                'menu_id' => 3,
                'descricao' => 'VISUALIZAR ACESSO',
                'identificador' => 'CONFIGURACAO_ACESSO_VISUALIZAR'
            ],
            [
                'menu_id' => 3,
                'descricao' => 'STATUS ACESSO',
                'identificador' => 'CONFIGURACAO_ACESSO_STATUS'
            ],
            [
                'menu_id' => 3,
                'descricao' => 'COPIAR ACESSO',
                'identificador' => 'CONFIGURACAO_ACESSO_COPIAR'
            ],  

        ]);

          DB::table('acesso_permissao')->insert([
          	[
                'acesso_id' => 1,
                'permissao_id' => 1
            ],
            [
                'acesso_id' => 1,
                'permissao_id' => 2
            ],
            [
                'acesso_id' => 1,
                'permissao_id' => 3
            ],
            [
                'acesso_id' => 1,
                'permissao_id' => 4
            ],
            [
                'acesso_id' => 1,
                'permissao_id' => 5
            ],
        ]);
    }
}
