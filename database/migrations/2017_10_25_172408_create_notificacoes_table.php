<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->string('titulo', 100);
            $table->text('mensagem');
            $table->string('modulo', 50);
            $table->string('url', 50);
            $table->string('icone', 50)->default('bell');
            $table->string('cor', 7)->default('#dd4b39');
            $table->boolean('visualizada')->default('false');
            $table->boolean('notificada')->default('false');
            $table->timestamp('dt_visualizada')->nullable();
            $table->timestamp('dt_notificada')->nullable();         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificacoes');
    }
}
