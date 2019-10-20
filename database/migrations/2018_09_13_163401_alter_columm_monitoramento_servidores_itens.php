<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColummMonitoramentoServidoresItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE monitoramento_servidores_itens ALTER COLUMN contador_falhas SET DEFAULT 0;");
        DB::statement("ALTER TABLE monitoramento_servidores_itens ALTER COLUMN dt_status SET DEFAULT now();");
        DB::statement("ALTER TABLE monitoramento_servidores ALTER COLUMN dt_status SET DEFAULT now();");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE monitoramento_servidores_itens ALTER COLUMN contador_falhas DROP DEFAULT");
        DB::statement("ALTER TABLE monitoramento_servidores_itens ALTER COLUMN dt_status DROP DEFAULT");
        DB::statement("ALTER TABLE monitoramento_servidores ALTER COLUMN dt_status DROP DEFAULT;");


    }
}
