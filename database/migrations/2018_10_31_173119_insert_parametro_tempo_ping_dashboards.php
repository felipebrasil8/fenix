<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertParametroTempoPingDashboards extends Migration
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
                'nome' => 'TEMPO_TIMEOUT_DASHBOARD_BASE_PUBLICACAO',
                'descricao' => 'VALOR EM SEGUNDOS PARA A COLETA DE DADOS DE PUBLICAÇÕES',
                'valor_texto' => NULL,
                'valor_numero' => 60,
                'valor_booleano' => NULL,
                'ordem' => 1110,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'TEMPO_TIMEOUT_DASHBOARD_TICKET',
                'descricao' => 'VALOR EM SEGUNDOS PARA A COLETA DE DADOS DE TICKETS',
                'valor_texto' => NULL,
                'valor_numero' => 60,
                'valor_booleano' => NULL,
                'ordem' => 1120,
                'editar' => true,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'TEMPO_TIMEOUT_TELA_SERVICO',
                'descricao' => 'VALOR EM SEGUNDOS PARA A COLETA DE DADOS DA TELA DE SERVIÇO',
                'valor_texto' => NULL,
                'valor_numero' => 60,
                'valor_booleano' => NULL,
                'ordem' => 1130,
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
        DB::table('parametros')->where('nome', 'TEMPO_TIMEOUT_DASHBOARD_BASE_PUBLICACAO')->delete();
        DB::table('parametros')->where('nome', 'TEMPO_TIMEOUT_DASHBOARD_TICKET')->delete();
        DB::table('parametros')->where('nome', 'TEMPO_TIMEOUT_TELA_SERVICO')->delete();
    }
}
