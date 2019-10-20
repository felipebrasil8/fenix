<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AjustePermissaoPerfilMonitoramento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $acesso_id = DB::table('acessos')->where('nome', 'TICKETS - VISUALIZAR TODOS OS TICKETS')->get()->first()->id;
        $perfil_id = DB::table('perfis')->where('nome', 'TELA DE MONITORAMENTO')->get()->first()->id;

        DB::table('acesso_perfil')->where('perfil_id', $perfil_id)->where('acesso_id', $acesso_id)->delete();

        $menu_id = DB::table('menus')->where('url', '/ticket/dashboard')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'VISUALIZAR DASHBOARD TODOS OS DEPARTAMENTOS',
                'identificador' => 'TICKETS_VISUALIZAR_DASHBOARD_TODOS_DEPARTAMENTOS'
            ]    
        ]);

        DB::table('acessos')->insert([
            [
                'nome' => 'TICKETS - VISUALIZAR DASHBOARD TODOS OS DEPARTAMENTOS'
            ]        
        ]);

        $permissao = DB::table('permissoes')->where('identificador', 'TICKETS_VISUALIZAR_DASHBOARD_TODOS_DEPARTAMENTOS')->get()->first()->id;
        $acesso_id = DB::table('acessos')->where('nome', 'TICKETS - VISUALIZAR DASHBOARD TODOS OS DEPARTAMENTOS')->get()->first()->id;

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao
            ], 
        
        ]);

        $perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_mon = DB::table('perfis')->where('nome', 'TELA DE MONITORAMENTO')->get()->first()->id;

        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_adm
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_mon
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
       
        $acesso_id = DB::table('acessos')->where('nome', 'TICKETS - VISUALIZAR TODOS OS TICKETS')->get()->first()->id;
        $perfil_id = DB::table('perfis')->where('nome', 'TELA DE MONITORAMENTO')->get()->first()->id;

        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_id
            ], 
        
        ]);

        $permissao = DB::table('permissoes')->where('identificador', 'TICKETS_VISUALIZAR_DASHBOARD_TODOS_DEPARTAMENTOS')->get()->first()->id;
        $acesso_id_todos = DB::table('acessos')->where('nome', 'TICKETS - VISUALIZAR DASHBOARD TODOS OS DEPARTAMENTOS')->get()->first()->id;
        
        $perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_mon = DB::table('perfis')->where('nome', 'TELA DE MONITORAMENTO')->get()->first()->id;

        DB::table('acesso_permissao')->where('permissao_id', $permissao)->where('acesso_id', $acesso_id_todos)->delete();
        DB::table('acesso_perfil')->where('perfil_id', $perfil_adm)->where('acesso_id', $acesso_id_todos)->delete();
        DB::table('acesso_perfil')->where('perfil_id', $perfil_mon)->where('acesso_id', $acesso_id_todos)->delete();


        DB::table('permissoes')->where('id', $permissao)->delete();
        DB::table('acessos')->where('id', $acesso_id_todos)->delete();

        

    }
}
