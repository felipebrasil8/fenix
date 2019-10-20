<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketPrioridadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_prioridade', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
            $table->boolean('ativo')->default('true');    
            $table->integer('departamento_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos'); 
            $table->string('nome', 50);
            $table->integer('prioridade');
            $table->string('cor', 7);

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
        Schema::dropIfExists('tickets_prioridade');
    }
}