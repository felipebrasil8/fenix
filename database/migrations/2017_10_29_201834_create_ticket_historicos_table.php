<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketHistoricosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_historico', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->now();
            $table->integer('usuario_id')->unsigned()->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        
            $table->integer('notificacao_id')->nullable();
            $table->foreign('notificacao_id')->references('id')->on('notificacoes');
            $table->integer('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->json('movimentacao')->nullable();
            $table->text('mensagem');
            $table->string('icone', 50);
            $table->string('cor', 15);
            $table->boolean('interacao')->nullable();
            $table->boolean('interno')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_historico');
    }
}
