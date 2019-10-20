<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardSprint024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        $menu_id = DB::table('menus')->where('nome', 'BASE DE CONHECIMENTO')->first()->id;

        DB::table('menus')->insert([
            
            'ativo' => 'TRUE',
            'menu_id' => $menu_id,
            'nome' => 'DASHBOARD',
            'descricao' => 'VISUALIZAR DASHBOARD',
            'icone' => 'dashboard',
            'url' => '/base-de-conhecimento/dashboard' ,
            'nivel' => 2,
            'ordem' => 105
        ]);
        
        $menu_dash_id = DB::table('menus')->where('nome', 'DASHBOARD')->where('menu_id', $menu_id)->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_dash_id,
                'descricao' => 'VISUALIZAR DASHBOARD',
                'identificador' => 'BASE_DASHBOARD_VISUALIZAR'
            ]        
        ]);

        DB::table('acessos')->insert([
            [
                'nome' => 'BASE DE CONHECIMENTO - VISUALIZAR DASHBOARD'
            ]
        ]);        

        $permissao_id = DB::table('permissoes')->where('identificador', 'BASE_DASHBOARD_VISUALIZAR')->get()->first()->id;
       
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - VISUALIZAR DASHBOARD')->get()->first()->id;

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_id
            ]
        ]);      

        $perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_gp = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        $perfil_gc = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;
        $perfil_tm = DB::table('perfis')->where('nome', 'TELA DE MONITORAMENTO')->get()->first()->id;
        
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
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_tm
            ],
        ]);                               

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - VISUALIZAR DASHBOARD')->get()->first()->id;
        $permissao_id = DB::table('permissoes')->where('identificador', 'BASE_DASHBOARD_VISUALIZAR')->get()->first()->id;

        DB::table('acesso_permissao')->where('permissao_id', $permissao_id)->delete();
        DB::table('acesso_perfil')->where('acesso_id', $acesso_id)->delete();
        DB::table('acessos')->where('id', $acesso_id)->delete();
        DB::table('permissoes')->where('id', $permissao_id)->delete();
        DB::table('menus')->where('url', '/base-de-conhecimento/dashboard')->delete();
    }
}




