<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration para criar o menu, permissão, acesso e vincular ao perfil ADMINISTRADOR

 */
class InsertAcessoPermissaoMenuItensMonitoradosSprint030 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $menu_id_principal = DB::table('menus')->where('nome', 'MONITORAMENTO')->where('nivel', 1)->first()->id;
        
        // CRIAÇÃO DO MENU DO TIPO FILHO
        DB::table('menus')->insert([
            [
                'nome' => 'ITENS MONITORADOS',
                'menu_id' => $menu_id_principal,    
                'descricao' => 'MONITORAMENTO DOS ITENS',   
                'icone' => 'list-ul',  
                'url' => '/monitoramento/itens',
                'nivel' => 2, 
                'ordem' => 145, 
            ]
        ]);

        $menu_id_itens = DB::table('menus')->where('url', '/monitoramento/itens')->first()->id;

        // CRIA A PERMISSÃO E VÍNCULA AO MENU
        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id_itens,
                'descricao' => 'VISUALIZAR ITENS DE MONITORAMENTO',
                'identificador' => 'MONITORAMENTO_ITENS_VISUALIZAR'
            ]   
        ]);

        // CRIA O ACESSO 
        DB::table('acessos')->insert([
            [
                'nome' => 'MONITORAMENTO - VISUALIZAR ITENS DE MONITORAMENTO DOS SERVIDORES',
            ]
        ]);

        $acesso_itens = DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR ITENS DE MONITORAMENTO DOS SERVIDORES')->get()->first()->id;
        $permissao_itens = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_ITENS_VISUALIZAR')->get()->first()->id;

        // APÓS ACESSO E PERMISSÃO SEREM CRIADOS, ELES SÃO VÍNCULADOS
        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_itens,
                'permissao_id' => $permissao_itens
            ]  
        ]);        

        $perfil_id_admin = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        
        // VÍNCULO O ACESSO CRIADO AOS PERFIS 
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_itens,
                'perfil_id' => $perfil_id_admin
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

        $menu_id_itens = DB::table('menus')->where('url', '/monitoramento/itens')->first()->id;
        $acesso_itens = DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR ITENS DE MONITORAMENTO DOS SERVIDORES')->get()->first()->id;
        $permissao_itens = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_ITENS_VISUALIZAR')->get()->first()->id;
        $perfil_id_admin = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;       

        DB::table('acesso_perfil')->where('perfil_id', $perfil_id_admin)->where('acesso_id', $acesso_itens)->delete();              
        DB::table('acesso_permissao')->where('permissao_id', $permissao_itens)->where('acesso_id', $acesso_itens)->delete();
        DB::table('acessos')->where('id', $acesso_itens)->delete();
        DB::table('permissoes')->where('id', $permissao_itens)->delete();
        DB::table('menus')->where('id', $menu_id_itens)->delete();

    }
}
