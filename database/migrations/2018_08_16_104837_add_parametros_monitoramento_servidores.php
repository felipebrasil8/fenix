<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParametrosMonitoramentoServidores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::table('parametros_grupo')->insert([
            [
                'nome' =>'MONITORAMENTO',
                'ativo' => 'TRUE'
            ]
        ]);

        $parametro_grupo_id = DB::table('parametros_grupo')->where('nome', 'MONITORAMENTO')->first()->id;
        
        $parametro_tipo_numero_id = DB::table('parametros_tipo')->where('nome', 'NÚMERO')->first()->id;
        $parametro_tipo_texto_id = DB::table('parametros_tipo')->where('nome', 'TEXTO')->first()->id;
        $parametro_tipo_boolean_id = DB::table('parametros_tipo')->where('nome', 'BOOLEANO')->first()->id;

        DB::table('parametros')->insert([
             [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_boolean_id,
                'nome' => 'MONITORAMENTO_SERVICO_ATIVO',
                'descricao' => 'SERVIÇO DE MONITORAMENTO ATIVO',
                'valor_texto' => NULL,
                'valor_numero' => NULL,
                'valor_booleano' => TRUE,
                'ordem' => 1010,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_REQUISICOES_SIMULTANEAS',
                'descricao' => 'QUANTIDADE DE REQUISIÇÕES SIMULTÂNEAS',
                'valor_texto' => NULL,
                'valor_numero' => 10,
                'valor_booleano' => NULL,
                'ordem' => 1020,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_INTERVALO_REQUISICOES',
                'descricao' => 'INTERVALO DE ESPERA ENTRE AS REQUISIÇÕES SIMULTÂNEAS',
                'valor_texto' => NULL,
                'valor_numero' => 3,
                'valor_booleano' => NULL,
                'ordem' => 1030,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_texto_id,
                'nome' => 'NOME_ITEM_REQUISICOES_UNICAS',
                'descricao' => 'NOME DO ITEM QUE SERÁ COLETADO NAS REQUISIÇÃOES ÚNICAS',
                'valor_texto' => 'chamadas_simultaneas',
                'valor_numero' => NULL,
                'valor_booleano' => NULL,
                'ordem' => 1040,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'QUANTIDADE_REQUISICOES_UNICAS',
                'descricao' => 'QUANTIDADE DE REQUISIÇÕES ÚNICAS ENTRE REQUISIÇÕES COMPLETAS',
                'valor_texto' => NULL,
                'valor_numero' => 4,
                'valor_booleano' => NULL,
                'ordem' => 1050,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_TIMEOUT_API_PRODUTOS',
                'descricao' => 'TIMEOUT UTILIZADO NA CHAMADA DA API NOS PRODUTOS',
                'valor_texto' => NULL,
                'valor_numero' => 10,
                'valor_booleano' => NULL,
                'ordem' => 1060,
                'editar' => true,
                'obrigatorio' => true
            ],  
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_ALARME_PING',
                'descricao' => 'VALOR EM MILISEGUNDOS PARA O ITEM PING ALARMAR',
                'valor_texto' => NULL,
                'valor_numero' => 500,
                'valor_booleano' => NULL,
                'ordem' => 1070,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_ALARME_PORTA',
                'descricao' => 'VALOR EM MILISEGUNDOS PARA O ITEM DE MONITORAMENTO DAS PORTAS ALARMAR',
                'valor_texto' => NULL,
                'valor_numero' => 500,
                'valor_booleano' => NULL,
                'ordem' => 1080,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_PORTA_NXT3000',
                'descricao' => 'PORTA PADRÃO DA API DO NXT3000',
                'valor_texto' => NULL,
                'valor_numero' => 9000,
                'valor_booleano' => NULL,
                'ordem' => 1090,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_PORTA_NXTDISCADOR',
                'descricao' => 'PORTA PADRÃO DA API DO NXT-DISCADOR',
                'valor_texto' => NULL,
                'valor_numero' => 9250,
                'valor_booleano' => NULL,
                'ordem' => 1100,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_PORTA_NXTURATIVA',
                'descricao' => 'PORTA PADRÃO DA API DA NXT-URATIVA',
                'valor_texto' => NULL,
                'valor_numero' => 9251,
                'valor_booleano' => NULL,
                'ordem' => 1110,
                'editar' => true,
                'obrigatorio' => true
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


        DB::table('parametros')->where('nome', 'MONITORAMENTO_SERVICO_ATIVO')->delete();
        DB::table('parametros')->where('nome', 'MONITORAMENTO_REQUISICOES_SIMULTANEAS')->delete();
        DB::table('parametros')->where('nome', 'MONITORAMENTO_INTERVALO_REQUISICOES')->delete();
        DB::table('parametros')->where('nome', 'NOME_ITEM_REQUISICOES_UNICAS')->delete();
        DB::table('parametros')->where('nome', 'QUANTIDADE_REQUISICOES_UNICAS')->delete();
        DB::table('parametros')->where('nome', 'MONITORAMENTO_TIMEOUT_API_PRODUTOS')->delete();
        DB::table('parametros')->where('nome', 'MONITORAMENTO_ALARME_PING')->delete();
        DB::table('parametros')->where('nome', 'MONITORAMENTO_ALARME_PORTA')->delete();
        DB::table('parametros')->where('nome', 'MONITORAMENTO_PORTA_NXT3000')->delete();
        DB::table('parametros')->where('nome', 'MONITORAMENTO_PORTA_NXTDISCADOR')->delete();
        DB::table('parametros')->where('nome', 'MONITORAMENTO_PORTA_NXTURATIVA')->delete();

        DB::table('parametros_grupo')->where('nome', 'MONITORAMENTO')->delete();

    }
}
