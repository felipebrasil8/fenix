<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketCamposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_campo', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
            $table->integer('departamento_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->boolean('ativo')->default('true');
            $table->string('nome', 50);
            $table->text('descricao');
            $table->string('padrao', 50)->nullable();
            $table->boolean('visivel')->default('true');
            $table->boolean('obrigatorio')->default('true');
            $table->enum('tipo', ['TEXTO', 'TEXTO LONGO', 'LISTA']);
            $table->json('dados');

            //Chave composta para não permitir a repetição nome/departamento
            $table->unique(['nome', 'departamento_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_campo');
    }
}
