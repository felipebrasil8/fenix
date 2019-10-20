<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoramentoServidoresColetas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        
        Schema::create('monitoramento_servidores_coletas', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
                        
            $table->integer('monitoramento_servidores_id')->unsigned()->nullable();
            $table->foreign('monitoramento_servidores_id')->references('id')->on('monitoramento_servidores');            

            $table->double('tempo_de_resposta')->nullable();

            $table->json('itens')->nullable();
            $table->json('configuracoes')->nullable();            
            
            $table->integer('monitoramento_servidores_status_id')->unsigned()->nullable();
            $table->foreign('monitoramento_servidores_status_id')->references('id')->on('monitoramento_servidores_status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoramento_servidores_coletas');
    }
}
