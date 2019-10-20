<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoramentoServidoresChamadosVinculadosHistoricoTableSprint031 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoramento_servidores_chamados', function (Blueprint $table) {

            $table->increments('id');

            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');

            $table->integer('monitoramento_servidores_id')->unsigned()->nullable();
            $table->foreign('monitoramento_servidores_id')->references('id')->on('monitoramento_servidores'); 

            $table->integer('monitoramento_servidores_itens_id')->unsigned()->nullable();
            $table->foreign('monitoramento_servidores_itens_id')->references('id')->on('monitoramento_servidores_itens'); 
            $table->string('numero_chamado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoramento_servidores_chamados');
    }
}