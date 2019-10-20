<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoramentoServidoresItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
      public function up()
    {


        
        Schema::create('monitoramento_servidores_itens', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            
            $table->integer('monitoramento_servidores_id')->unsigned()->nullable();
            $table->foreign('monitoramento_servidores_id')->references('id')->on('monitoramento_servidores');    

            $table->integer('monitoramento_servidores_status_id')->unsigned()->nullable();
            $table->foreign('monitoramento_servidores_status_id')->references('id')->on('monitoramento_servidores_status');
            $table->timestamp('dt_status')->unsigned()->nullable();

            $table->string('identificador');
            $table->string('nome');
            $table->text('mensagem')->nullable();
            $table->json('valores')->nullable();
            $table->integer('contador_falhas')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoramento_servidores_itens');
    }
}
