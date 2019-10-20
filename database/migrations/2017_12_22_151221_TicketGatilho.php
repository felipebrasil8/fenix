<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketGatilho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('tickets_gatilho', function (Blueprint $table) {
            $table->increments('id');
            
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->useCurrent()->unsigned()->nullable();
           
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
          
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');

            $table->integer('departamento_id')->nullable();
            $table->foreign('departamento_id')->references('id')->on('departamentos'); 
            
            $table->string('nome', 50)->unique();
            
            $table->boolean('ativo')->default('true');            
            $table->integer('ordem')->unsigned();
            
            $table->json('quanto_executar');
            $table->json('acao');
          
        });      

        Schema::table('tickets', function (Blueprint $table) {
            $table->integer('notificacao_id')->unsigned()->nullable();
            $table->foreign('notificacao_id')->references('id')->on('notificacoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_gatilho');

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('notificacao_id');
        });
    }
}
