<?php

use Illuminate\Database\Seeder;

class PermissaoFuncionarioSeeder extends Seeder
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
                'menu_id' => 7,
                'descricao' => 'CADASTRAR FUNCIONÁRIO',
                'identificador' => 'RH_FUNCIONARIO_CADASTRAR'
            ],
            [
                'menu_id' => 7,
                'descricao' => 'EDITAR FUNCIONÁRIO',
                'identificador' => 'RH_FUNCIONARIO_EDITAR'
            ],
            [
                'menu_id' => 7,
                'descricao' => 'VISUALIZAR FUNCIONÁRIO',
                'identificador' => 'RH_FUNCIONARIO_VISUALIZAR'
            ],
            [
                'menu_id' => 7,
                'descricao' => 'STATUS FUNCIONÁRIO',
                'identificador' => 'RH_FUNCIONARIO_STATUS'
            ],
            [
                'menu_id' => 7,
                'descricao' => 'EDITAR AVATAR FUNCIONÁRIO',
                'identificador' => 'RH_FUNCIONARIO_EDITAR_AVATAR'
            ],
             [
                'menu_id' => 7,
                'descricao' => 'ABA DE DADOS DE CADASTRO',
                'identificador' => 'RH_FUNCIONARIO_ABA_DADOS_DE_CADASTRO'
            ],
            [
                'menu_id' => 7,
                'descricao' => 'ABA DE DADOS PESSOAIS',
                'identificador' => 'RH_FUNCIONARIO_ABA_DADOS_PESSOAIS'
            ],
            [
                'menu_id' => 7,
                'descricao' => 'ABA DE CONTATO',
                'identificador' => 'RH_FUNCIONARIO_ABA_CONTATO'
            ]
            
            
        ]);
         DB::table('acesso_permissao')->insert([
        	[
	            'acesso_id' => 3,
                'permissao_id' => 20
            ],
            [
                'acesso_id' => 3,
                'permissao_id' => 21
            ],
            [
                'acesso_id' => 3,
                'permissao_id' => 22
            ],
            [
                'acesso_id' => 3,
                'permissao_id' => 23
            ],
            [
                'acesso_id' => 3,
                'permissao_id' => 24
            ],
            [
                'acesso_id' => 3,
                'permissao_id' => 25
            ],
             [
                'acesso_id' => 3,
                'permissao_id' => 26
            ],
            [
                'acesso_id' => 3,
                'permissao_id' => 27
            ],
            [
                'acesso_id' => 5,
                'permissao_id' => 22
            ],
             [
                'acesso_id' => 5,
                'permissao_id' => 26
            ],
             [
                'acesso_id' => 5,
                'permissao_id' => 27
            ]                  


        ]);
    }
}
