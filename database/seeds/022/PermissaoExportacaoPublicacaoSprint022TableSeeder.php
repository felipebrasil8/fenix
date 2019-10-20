<?php

use Illuminate\Database\Seeder;

class PermissaoExportacaoPublicacaoSprint022TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //busca o id de acesso, de BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES')->get()->first()->id;

        //criar a permissao para exportar dados da publicação vinculando ao menu de publicações
        DB::table('permissoes')->insert([
            [
                'menu_id' => 23,
                'descricao' => 'EXPORTAR DADOS DAS PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_EXPORTAR'
            ]          
        ]);

        //busca o id criado de BASE_PUBLICACOES_EXPORTAR
        $permissao_id = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_EXPORTAR')->get()->first()->id;

        //vincula ao acesso x permissao
        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_id
            ]
        ]);

    }
}
