<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',9)->unique();
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
            $table->integer('usuario_responsavel_id')->nullable();
            $table->foreign('usuario_responsavel_id')->references('id')->on('usuarios');
            $table->integer('usuario_solicitante_id');
            $table->foreign('usuario_solicitante_id')->references('id')->on('usuarios');
            $table->integer('departamento_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->integer('tickets_categoria_id');
            $table->foreign('tickets_categoria_id')->references('id')->on('tickets_categoria');
            $table->integer('tickets_status_id');
            $table->foreign('tickets_status_id')->references('id')->on('tickets_status');
            $table->integer('tickets_prioridade_id');
            $table->foreign('tickets_prioridade_id')->references('id')->on('tickets_prioridade');
            $table->integer('avaliacao')->nullable();
            $table->string('assunto', 100);
            $table->text('descricao');
            $table->timestamp('dt_resolucao')->unsigned()->nullable();
            $table->timestamp('dt_fechamento')->unsigned()->nullable();
            $table->timestamp('dt_previsao')->unsigned()->nullable();
            $table->timestamp('dt_notificacao')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
