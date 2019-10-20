<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsAcaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_acao', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
            $table->text('nome');
            $table->integer('departamento_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->boolean('ativo')->default('true');
            $table->integer('ordem')->unsigned();
            $table->boolean('solicitante_executa')->default('true');
            $table->boolean('responsavel_executa')->default('true');
            $table->boolean('trata_executa')->default('true');
            $table->boolean('interacao')->default('true');
            $table->boolean('nota_interna')->default('true');
            $table->json('campos');
            $table->json('status_atual');
            $table->json('status_novo');
            $table->string('icone', 50)->default('fa-reply');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_acao');
    }
}
