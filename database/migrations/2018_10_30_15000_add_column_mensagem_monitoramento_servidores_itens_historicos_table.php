<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMensagemMonitoramentoServidoresItensHistoricosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramento_servidores_itens_historicos', function (Blueprint $table) {                     
            $table->text('mensagem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("monitoramento_servidores_itens_historicos", function ($table) {
            $table->dropColumn('mensagem');         
        });
    }
}
