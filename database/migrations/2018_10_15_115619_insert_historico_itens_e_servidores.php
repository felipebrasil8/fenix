<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertHistoricoItensEServidores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "  
                INSERT INTO monitoramento_servidores_itens_historicos ( servidores_id, servidores_itens_id, servidores_status_id, data_entrada  ) 
                SELECT 
                    monitoramento_servidores_id,
                    id,  
                    monitoramento_servidores_status_id,
                    dt_status 
                FROM monitoramento_servidores_itens 
            "
        );
        DB::statement(
            "  
                INSERT INTO monitoramento_servidores_itens_historicos ( servidores_id, servidores_itens_id, servidores_status_id, data_entrada  ) 
                SELECT 
                    id,
                    NULL AS servidores_itens_id,  
                    monitoramento_servidores_status_id,
                    dt_status 
                FROM monitoramento_servidores 
            "
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('monitoramento_servidores_itens_historicos')->delete();
    }
}







