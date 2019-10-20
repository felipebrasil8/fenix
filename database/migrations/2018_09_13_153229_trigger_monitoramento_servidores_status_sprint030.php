<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerMonitoramentoServidoresStatusSprint030 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TRIGGER triger_monitoramento_servidores_status AFTER UPDATE OF dt_ultima_coleta ON monitoramento_servidores FOR EACH ROW EXECUTE PROCEDURE atualiza_dt_status_servidores();
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
            DROP TRIGGER IF EXISTS triger_monitoramento_servidores_status ON monitoramento_servidores CASCADE;
        ");
    }
}
