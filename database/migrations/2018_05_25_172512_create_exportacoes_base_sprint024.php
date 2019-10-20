<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExportacoesBaseSprint024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Busca ID do menu BASE DE CONHECIMENTO
        $menu_id = DB::table('menus')->where('nome', 'BASE DE CONHECIMENTO')->first()->id;

        DB::table('menus')->insert([
            [
                'menu_id' => $menu_id,
                'nome' => 'EXPORTAÇÕES',
                'descricao' => 'EXPORTAÇÕES DA BASE DE CONHECIMENTO',
                'icone' => 'download',
                'url' => '/base-de-conhecimento/exportacoes',
                'nivel' => '2',
                'ordem' => 130
            ]        
        ]);

        // Busca ID do menu BASE DE CONHECIMENTO
        $menu_id = DB::table('menus')->where('descricao', 'EXPORTAÇÕES DA BASE DE CONHECIMENTO')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'VISUALIZAR PÁGINA DE EXPORTAÇÕES',
                'identificador' => 'BASE_EXPORTACOES'
            ],
            [
                'menu_id' => $menu_id,
                'descricao' => 'EXPORTAR DADOS DE VISUALIZAÇÃO',
                'identificador' => 'BASE_EXPORTACOES_VISUALIZACOES'
            ]       
        ]);

        DB::table('acessos')->insert([
            [
                'nome' => 'BASE DE CONHECIMENTO - EXPORTAR DADOS'
            ]        
        ]);

        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - EXPORTAR DADOS')->get()->first()->id;

        $permissao_exp_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES')->get()->first()->id;
        $permissao_exp_vis_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_VISUALIZACOES')->get()->first()->id;

        //vincula ao acesso x permissao
        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_exp_id
            ], 
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_exp_vis_id
            ]
        ]);

        $perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_gp = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        $perfil_gc = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;
        
        // Vincular perfis com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_adm
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_gp
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_gc
            ],
        ]);

        DB::table('permissoes')->where('identificador','BASE_PUBLICACOES_EXPORTAR')->update([
            'menu_id' => $menu_id,
            'identificador' => 'BASE_EXPORTACOES_PUBLICACOES'
        ]);

        DB::table('permissoes')->where('identificador','BASE_PUBLICACOES_EXPORTAR_PESQUISA')->update([
            'menu_id' => $menu_id,
            'identificador' => 'BASE_EXPORTACOES_PESQUISAS'
        ]);

        $permissao_exp_pub_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_PUBLICACOES')->get()->first()->id;
        $permissao_exp_pes_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_PESQUISAS')->get()->first()->id;

        $acesso_ger_pub_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES')->get()->first()->id;

        DB::table('acesso_permissao')->where('permissao_id', $permissao_exp_pub_id)->where('acesso_id', $acesso_ger_pub_id)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_exp_pes_id)->where('acesso_id', $acesso_ger_pub_id)->delete();

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_exp_pub_id
            ], 
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_exp_pes_id
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $permissao_exp_pub_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_PUBLICACOES')->get()->first()->id;
        $permissao_exp_pes_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_PESQUISAS')->get()->first()->id;

        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - EXPORTAR DADOS')->get()->first()->id;

        DB::table('acesso_permissao')->where('permissao_id', $permissao_exp_pub_id)->where('acesso_id', $acesso_id)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_exp_pes_id)->where('acesso_id', $acesso_id)->delete();

        $acesso_ger_pub_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES')->get()->first()->id;

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_ger_pub_id,
                'permissao_id' => $permissao_exp_pub_id
            ], 
            [
                'acesso_id' => $acesso_ger_pub_id,
                'permissao_id' => $permissao_exp_pes_id
            ]
        ]);

        // Volta permissoes antigas de exportacao
        $menu_id = DB::table('menus')->where('nome', 'PUBLICAÇÕES')->first()->id;

        DB::table('permissoes')->where('identificador','BASE_EXPORTACOES_PUBLICACOES')->update([
            'menu_id' => $menu_id,
            'identificador' => 'BASE_PUBLICACOES_EXPORTAR'
        ]);

        DB::table('permissoes')->where('identificador','BASE_EXPORTACOES_PESQUISAS')->update([
            'menu_id' => $menu_id,
            'identificador' => 'BASE_PUBLICACOES_EXPORTAR_PESQUISA'
        ]);

        //Deleta relaração de perfis com acesso
        $perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_gp = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        $perfil_gc = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;
        
        DB::table('acesso_perfil')->where('perfil_id', $perfil_adm)->where('acesso_id', $acesso_id)->delete();
        DB::table('acesso_perfil')->where('perfil_id', $perfil_gp)->where('acesso_id', $acesso_id)->delete();
        DB::table('acesso_perfil')->where('perfil_id', $perfil_gc)->where('acesso_id', $acesso_id)->delete();

        // Deleta relacao de permissoes novas com acesso
        $permissao_exp_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES')->get()->first()->id;
        $permissao_exp_vis_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_VISUALIZACOES')->get()->first()->id;

        DB::table('acesso_permissao')->where('permissao_id', $permissao_exp_id)->where('acesso_id', $acesso_id)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_exp_vis_id)->where('acesso_id', $acesso_id)->delete();

        // Deleta Acesso
        DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - EXPORTAR DADOS')->delete();

        // Deleta Permissoes
        DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES')->delete();
        DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_VISUALIZACOES')->delete();

        // Deleta Menu
        DB::table('menus')->where('descricao', 'EXPORTAÇÕES DA BASE DE CONHECIMENTO')->delete();
    }
}
