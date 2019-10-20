<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogMonitoramentoServidores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_monitoramento_servidores', function (Blueprint $table) {
            
            $table->increments('id');

            $table->timestamp('inicio_coleta');
            $table->timestamp('fim_coleta');

            $table->integer('duracao');
            
            $table->integer('requisicoes_simultaneas');
            $table->integer('intervalo_requisicoes');            
            $table->integer('qtde_servidores');
            $table->integer('timeout');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_monitoramento_servidores');
    }
}

