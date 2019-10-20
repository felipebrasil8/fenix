<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoramentoServidores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

     
        Schema::create('monitoramento_servidores', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->softDeletes();
          
            $table->integer('id_projeto')->unsigned()->nullable();
            $table->string('tipo', 50);
            
            $table->integer('porta_api')->unsigned()->nullable();
            
            $table->integer('monitoramento_servidores_status_id')->unsigned()->nullable();
            $table->foreign('monitoramento_servidores_status_id')->references('id')->on('monitoramento_servidores_status');
            $table->timestamp('dt_status')->unsigned()->nullable();
            
            $table->timestamp('dt_ultima_coleta')->unsigned()->nullable();

            $table->integer('contador_falhas')->nullable()->default(0);
            $table->integer('contador_coletas')->nullable()->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoramento_servidores');
    }
}
