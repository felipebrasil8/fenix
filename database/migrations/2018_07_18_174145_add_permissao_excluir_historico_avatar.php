<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * - Criar uma permissão nova: 
 * >> descrição: EXCLUIR HISTÓRICO DE AVATAR 
 * >> identificador: RH_FUNCIONARIO_EXCLUIR_HISTORICO_AVATAR 
 * >> menu: FUNCIONÁRIOS 
 * >> Vincular ao acesso PESSOAS - ADMINISTRAR FUNCIONÁRIOS 
 */
class AddPermissaoExcluirHistoricoAvatar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu_id = DB::table('menus')->where('url', '/rh/funcionario')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'EXCLUIR HISTÓRICO DE AVATAR',
                'identificador' => 'RH_FUNCIONARIO_EXCLUIR_HISTORICO_AVATAR'
            ],                
        ]);

        $acesso_pessoas_administrar_funcionarios_id = DB::table('acessos')->where('nome', 'PESSOAS - ADMINISTRAR FUNCIONÁRIOS')->get()->first()->id;

        $permissao_excluir_historico_avatar = DB::table('permissoes')->where('identificador', 'RH_FUNCIONARIO_EXCLUIR_HISTORICO_AVATAR')->get()->first()->id;        

        DB::table('acesso_permissao')->insert([            
            [
                'acesso_id' => $acesso_pessoas_administrar_funcionarios_id,
                'permissao_id' => $permissao_excluir_historico_avatar
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
        $acesso_pessoas_administrar_funcionarios_id = DB::table('acessos')->where('nome', 'PESSOAS - ADMINISTRAR FUNCIONÁRIOS')->get()->first()->id;      
        
        $permissao_aba_histotico_avatar_id = DB::table('permissoes')->where('identificador', 'RH_FUNCIONARIO_EXCLUIR_HISTORICO_AVATAR')->get()->first()->id;        
        
        DB::table('acesso_permissao')->where('permissao_id', $permissao_aba_histotico_avatar_id)->where('acesso_id', $acesso_pessoas_administrar_funcionarios_id)->delete();        

        DB::table('permissoes')->where('id', $permissao_aba_histotico_avatar_id)->delete();
    }
}
