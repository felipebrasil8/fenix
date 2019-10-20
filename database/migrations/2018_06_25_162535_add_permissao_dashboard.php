<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissaoDashboard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        $menu_id = DB::table('menus')->where('url', '/base-de-conhecimento/dashboard')->first()->id;
   
        DB::table('acessos')->insert([
            [
                'nome' => 'BASE DE CONHECIMENTO - ACESSOS GERENCIAIS DO DASHBOARD',
            ]
          
        ]);

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'EXPORTAR DADOS DO DASHBOARD',
                'identificador' => 'BASE_DASHBOARD_EXPORTAR'
            ],
            [
                'menu_id' => $menu_id,
                'descricao' => 'TRATAR PESQUISAS NO DASHBOARD',
                'identificador' => 'BASE_DASHBOARD_TRATAR_PESQUISAS'
            ],
            [
                'menu_id' => $menu_id,
                'descricao' => 'TRATAR MENSAGENS NO DASHBOARD',
                'identificador' => 'BASE_DASHBOARD_TRATAR_MENSAGENS'
            ]    
        ]);
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - ACESSOS GERENCIAIS DO DASHBOARD')->get()->first()->id;

        $permissao_dash_exportar = DB::table('permissoes')->where('identificador', 'BASE_DASHBOARD_EXPORTAR')->get()->first()->id;
        $permissao_dash_pesquisa = DB::table('permissoes')->where('identificador', 'BASE_DASHBOARD_TRATAR_PESQUISAS')->get()->first()->id;
        $permissao_dash_mensagem = DB::table('permissoes')->where('identificador', 'BASE_DASHBOARD_TRATAR_MENSAGENS')->get()->first()->id;


        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_dash_exportar
            ], 
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_dash_pesquisa
            ],  
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_dash_mensagem
            ]

        ]);        

        $perfil_id_admin = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_id_pessoas = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        $perfil_id_gestao = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;

        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_id_admin
            ], 
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_id_pessoas
            ],  
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_id_gestao
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


        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - ACESSOS GERENCIAIS DO DASHBOARD')->get()->first()->id;

        $perfil_id_admin = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_id_pessoas = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        $perfil_id_gestao = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;
        
        $permissao_dash_exportar = DB::table('permissoes')->where('identificador', 'BASE_DASHBOARD_EXPORTAR')->get()->first()->id;
        $permissao_dash_pesquisa = DB::table('permissoes')->where('identificador', 'BASE_DASHBOARD_TRATAR_PESQUISAS')->get()->first()->id;
        $permissao_dash_mensagem = DB::table('permissoes')->where('identificador', 'BASE_DASHBOARD_TRATAR_MENSAGENS')->get()->first()->id;


        DB::table('acesso_permissao')->where('permissao_id', $permissao_dash_exportar)->where('acesso_id', $acesso_id)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_dash_pesquisa)->where('acesso_id', $acesso_id)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_dash_mensagem)->where('acesso_id', $acesso_id)->delete();

        DB::table('acesso_perfil')->where('perfil_id', $perfil_id_admin)->where('acesso_id', $acesso_id)->delete();
        DB::table('acesso_perfil')->where('perfil_id', $perfil_id_pessoas)->where('acesso_id', $acesso_id)->delete();
        DB::table('acesso_perfil')->where('perfil_id', $perfil_id_gestao)->where('acesso_id', $acesso_id)->delete();

        DB::table('acessos')->where('id', $acesso_id)->delete();

        DB::table('permissoes')->where('id', $permissao_dash_exportar)->delete();
        DB::table('permissoes')->where('id', $permissao_dash_pesquisa)->delete();
        DB::table('permissoes')->where('id', $permissao_dash_mensagem)->delete();



    }
}
