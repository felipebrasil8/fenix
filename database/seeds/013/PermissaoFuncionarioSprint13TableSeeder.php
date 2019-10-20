<?php

use Illuminate\Database\Seeder;

class PermissaoFuncionarioSprint13TableSeeder extends Seeder
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
                'descricao' => 'ABA DE DADOS DO FUNCIONÁRIO',
                'identificador' => 'RH_FUNCIONARIO_ABA_DADOS_FUNCIONARIO'
            ],
            [
                'menu_id' => 7,
                'descricao' => 'EXPORTAR DADOS DO FUNCIONÁRIO',
                'identificador' => 'RH_FUNCIONARIO_EXPORTAR_DADOS_FUNCIONARIO'
            ]
                 
        ]);
        
        DB::table('acesso_permissao')->insert([
        	[
	            'acesso_id' => 3,
                'permissao_id' => 60
            ],
            [
                'acesso_id' => 3,
                'permissao_id' => 61
            ]

        ]);
    }
}
