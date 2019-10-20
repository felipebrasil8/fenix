<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertParametroSprint030 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $parametro_grupo_id = DB::table('parametros_grupo')->where('nome', 'MONITORAMENTO')->first()->id;
        
        $parametro_tipo_numero_id = DB::table('parametros_tipo')->where('nome', 'NÚMERO')->first()->id;
        
        DB::table('parametros')->insert([
             [
                'parametro_grupo_id' => $parametro_grupo_id,
                'parametro_tipo_id' => $parametro_tipo_numero_id,
                'nome' => 'MONITORAMENTO_PORTA_NXT3000_REDUNDANTE',
                'descricao' => 'PORTA PADRÃO DA API DO NXT3000 REDUNDANTE',
                'valor_texto' => NULL,
                'valor_numero' => 9003,
                'valor_booleano' => NULL,
                'ordem' => 1120,
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
        DB::table('parametros')->where('nome', 'MONITORAMENTO_PORTA_NXT3000_REDUNDANTE')->delete();
    }
}
