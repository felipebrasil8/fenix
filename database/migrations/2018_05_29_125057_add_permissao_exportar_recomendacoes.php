<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissaoExportarRecomendacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - EXPORTAR DADOS')->get()->first()->id;
        $menu_id = DB::table('menus')->where('descricao', 'EXPORTAÇÕES DA BASE DE CONHECIMENTO')->first()->id;

         DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'EXPORTAR DADOS DE RECOMENDAÇÃO',
                'identificador' => 'BASE_EXPORTACOES_RECOMENDACOES'
            ]    
        ]);

        $permissao_exp_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_RECOMENDACOES')->get()->first()->id;


        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_exp_id
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

        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - EXPORTAR DADOS')->get()->first()->id;
        $permissao_exp_id = DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_RECOMENDACOES')->get()->first()->id;
        
        DB::table('acesso_permissao')->where('permissao_id', $permissao_exp_id)->where('acesso_id', $acesso_id)->delete();
        DB::table('permissoes')->where('identificador', 'BASE_EXPORTACOES_RECOMENDACOES')->delete();
        
    }
}
