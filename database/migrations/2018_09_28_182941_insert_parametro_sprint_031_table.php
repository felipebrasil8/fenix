<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertParametroSprint031Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //criar parâmetro para configurar a partir de quantas coletas serão consideradas persistentes 
        $parametro_grupo_id = DB::table('parametros_grupo')->where('nome', 'MONITORAMENTO')->first()->id;
        
        $parametro_tipo_numero_id = DB::table('parametros_tipo')->where('nome', 'NÚMERO')->first()->id;
        
        DB::table('parametros')->insert([
             [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_QUANTIDADE_COLETA_FALHA_PERSISTENTE',
                'descricao' => 'VALOR DE QUANTAS COLETAS COM FALHAS PARA SE TORNAR PERSISTENTE',
                'valor_texto' => NULL,
                'valor_numero' => 5,
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
        DB::table('parametros')->where('nome', 'MONITORAMENTO_QUANTIDADE_COLETA_FALHA_PERSISTENTE')->delete();
    }

}
