<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerMonitoramentoServidoresItensStatusSprint030 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TRIGGER triger_monitoramento_servidores_itens_status AFTER UPDATE OF updated_at ON monitoramento_servidores_itens FOR EACH ROW EXECUTE 
            PROCEDURE atualiza_dt_status_itens();
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            DROP TRIGGER IF EXISTS triger_monitoramento_servidores_itens_status ON monitoramento_servidores_itens CASCADE;
        ");
    }
}
