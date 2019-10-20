<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketImagemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_imagem', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('ticket_id')->unsigned();
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->text('texto');
            $table->text('imagem');
            $table->text('imagem_miniatura');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_imagem');
    }
}
