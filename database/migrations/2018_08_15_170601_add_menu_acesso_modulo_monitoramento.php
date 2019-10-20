<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuAcessoModuloMonitoramento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        DB::table('menus')->insert([
            [
                'nome' => 'MONITORAMENTO',    
                'descricao' => 'MONITORAMENTO PRODUTOS CLIENTES',   
                'icone' => 'dashboard',  
                'url' => '',
                'nivel' => 1,   
                'ordem' => 1145,
            ]
        ]);

        $menu_id_principal = DB::table('menus')->where('nome', 'MONITORAMENTO')->where('nivel', 1)->first()->id;
        
        DB::table('menus')->insert([
            [
                'nome' => 'SERVIÇO',
                'menu_id' => $menu_id_principal,    
                'descricao' => 'SERVIÇO DE MONITORAMENTO',   
                'icone' => 'android',  
                'url' => '/monitoramento/servico',
                'nivel' => 2,   
                'ordem' => 150,
            ],
            [
                'nome' => 'SERVIDORES',
                'menu_id' => $menu_id_principal,    
                'descricao' => 'SERVIDORES MONITORADOS',   
                'icone' => 'server',  
                'url' => '/monitoramento/servidores',
                'nivel' => 2,   
                'ordem' => 140,
            ]
        ]);


        $menu_id_servico = DB::table('menus')->where('url', '/monitoramento/servico')->first()->id;
        $menu_id_servidores = DB::table('menus')->where('url', '/monitoramento/servidores')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id_servico,
                'descricao' => 'VISUALIZAR MONITORAMENTO DO SERVIÇO',
                'identificador' => 'MONITORAMENTO_SERVICO_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id_servidores,
                'descricao' => 'VISUALIZAR SERVIDORES',
                'identificador' => 'MONITORAMENTO_SERVIDORES_VISUALIZAR'
            ]
                
        ]);


        DB::table('acessos')->insert([
            [
                'nome' => 'MONITORAMENTO - VISUALIZAR MONITORAMENTO DOS SERVIDORES',
            ],
            [
                'nome' => 'MONITORAMENTO - VISUALIZAR STATUS DO SERVIÇO DE MONITORAMENTO',
            ]
          
        ]);


        $acesso_servidor = DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR MONITORAMENTO DOS SERVIDORES')->get()->first()->id;
        $acesso_servico = DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR STATUS DO SERVIÇO DE MONITORAMENTO')->get()->first()->id;

        $permissao_servico = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVICO_VISUALIZAR')->get()->first()->id;
        $permissao_servidor = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_VISUALIZAR')->get()->first()->id;
        

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_servico,
                'permissao_id' => $permissao_servico
            ], 
            [
                'acesso_id' => $acesso_servidor,
                'permissao_id' => $permissao_servidor
            ]  
        ]);        

        $perfil_id_admin = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
      

        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_servico,
                'perfil_id' => $perfil_id_admin
            ], 
            [
                'acesso_id' => $acesso_servidor,
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


        $menu_id_principal = DB::table('menus')->where('nome', 'MONITORAMENTO')->where('nivel', 1)->first()->id;
        $menu_id_servico = DB::table('menus')->where('url', '/monitoramento/servico')->first()->id;
        $menu_id_servidores = DB::table('menus')->where('url', '/monitoramento/servidores')->first()->id;
        
        $acesso_servidor = DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR MONITORAMENTO DOS SERVIDORES')->get()->first()->id;
        $acesso_servico = DB::table('acessos')->where('nome', 'MONITORAMENTO - VISUALIZAR STATUS DO SERVIÇO DE MONITORAMENTO')->get()->first()->id;

        $permissao_servico = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVICO_VISUALIZAR')->get()->first()->id;
        $permissao_servidor = DB::table('permissoes')->where('identificador', 'MONITORAMENTO_SERVIDORES_VISUALIZAR')->get()->first()->id;

        $perfil_id_admin = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;


        DB::table('acesso_permissao')->where('permissao_id', $permissao_servidor)->where('acesso_id', $acesso_servidor)->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao_servico)->where('acesso_id', $acesso_servico)->delete();
       
        DB::table('acesso_perfil')->where('perfil_id', $perfil_id_admin)->where('acesso_id', $acesso_servidor)->delete();
        DB::table('acesso_perfil')->where('perfil_id', $perfil_id_admin)->where('acesso_id', $acesso_servico)->delete();

        DB::table('acessos')->where('id', $acesso_servidor)->delete();
        DB::table('acessos')->where('id', $acesso_servico)->delete();

        DB::table('permissoes')->where('id', $permissao_servico)->delete();
        DB::table('permissoes')->where('id', $permissao_servidor)->delete();


        DB::table('menus')->where('id', $menu_id_servico)->delete();
        DB::table('menus')->where('id', $menu_id_servidores)->delete();
        DB::table('menus')->where('id', $menu_id_principal)->delete();


        
    }
}
