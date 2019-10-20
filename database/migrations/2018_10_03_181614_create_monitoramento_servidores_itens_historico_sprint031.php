<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoramentoServidoresItensHistoricoSprint031 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoramento_servidores_itens_historicos', function (Blueprint $table) {

            $table->increments('id');

            $table->timestamp('data_entrada')->useCurrent()->unsigned()->nullable();
            $table->timestamp('data_saida')->unsigned()->nullable();

            $table->integer('servidores_id')->unsigned()->nullable();
            $table->foreign('servidores_id')->references('id')->on('monitoramento_servidores'); 

            $table->integer('servidores_itens_id')->unsigned()->nullable();
            $table->foreign('servidores_itens_id')->references('id')->on('monitoramento_servidores_itens'); 

            $table->integer('servidores_status_id')->unsigned()->nullable();
            $table->foreign('servidores_status_id')->references('id')->on('monitoramento_servidores_status');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoramento_servidores_itens_historicos');
    }
}
