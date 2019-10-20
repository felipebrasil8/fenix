<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_categoria', function (Blueprint $table) {
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
            $table->integer('ticket_categoria_id')->nullable();
            $table->foreign('ticket_categoria_id')->references('id')->on('tickets_categoria'); 
            $table->string('nome', 50);
            $table->text('descricao');
            $table->text('dicas')->nullable();

            //Chave composta para não permitir a repetição nome/departamento/categoria
           // $table->unique(['nome',COALESCE('departamento_id', 0),'ticket_categoria_id']);
            
        });          

         DB::statement("CREATE UNIQUE INDEX tickets_categoria_nome_departamento_id_unique3 ON tickets_categoria (nome, COALESCE(ticket_categoria_id, 0), departamento_id);");
        


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_categoria');
    }
}
