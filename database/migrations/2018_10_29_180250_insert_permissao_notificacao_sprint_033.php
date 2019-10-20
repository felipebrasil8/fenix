<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertPermissaoNotificacaoSprint033 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $parametro_grupo_id = DB::table('parametros_grupo')->where('nome', 'SERVIDOR')->first()->id;
        
        $parametro_tipo_numero_id = DB::table('parametros_tipo')->where('nome', 'NÚMERO')->first()->id;
        
        DB::table('parametros')->insert([
             [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'TEMPO_COLETA_NOTIFICACAO',
                'descricao' => 'VALOR EM SEGUNDOS PARA COLETA DE NOTIFICAÇÃO',
                'valor_texto' => NULL,
                'valor_numero' => 60,
                'valor_booleano' => NULL,
                'ordem' => 1020,
                'editar' => true,
                'obrigatorio' => true
            ],

        ]);


        $menu = DB::table('menus')->where('url', '/configuracao/sistema/parametro')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu,
                'descricao' => 'VISUALIZAR NOTIFICAÇÃO',
                'identificador' => 'NOTIFICACAO_VISUALIZAR'
            ],
        ]);

         //ACESSO - PARADA PROGRAMADA   
        $acesso = DB::table('acessos')->where('nome', 'ACESSO BÁSICO')->get()->first()->id;
        $permissao = DB::table('permissoes')->where('identificador', 'NOTIFICACAO_VISUALIZAR')->get()->first()->id;
         // APÓS ACESSO E PERMISSÃO SEREM CRIADOS, ELES SÃO VÍNCULADOS
        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso,
                'permissao_id' => $permissao
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
        
        $permissao = DB::table('permissoes')->where('identificador', 'NOTIFICACAO_VISUALIZAR')->get()->first()->id;
        
        DB::table('acesso_permissao')->where( 'permissao_id', $permissao )->delete();
        DB::table('permissoes')->where('identificador', 'NOTIFICACAO_VISUALIZAR')->delete();
        DB::table('parametros')->where('nome', 'TEMPO_COLETA_NOTIFICACAO')->delete();
        
        
    }
}
