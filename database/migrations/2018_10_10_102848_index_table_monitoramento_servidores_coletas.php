<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexTableMonitoramentoServidoresColetas extends Migration
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
                CREATE INDEX monitoramento_servidores_coletas_idx01 ON monitoramento_servidores_coletas (date(created_at));
            "
        );
        DB::statement(
            "  
                CREATE INDEX logs_monitoramento_servidores_idx01 ON logs_monitoramento_servidores (date(inicio_coleta));
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
        DB::statement(
            " 
                DROP INDEX IF EXISTS monitoramento_servidores_coletas_idx01;
            "
        );
        DB::statement(
            " 
                DROP INDEX IF EXISTS logs_monitoramento_servidores_idx01;   
            "
        );
    }
}
