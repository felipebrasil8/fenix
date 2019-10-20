<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
            $table->boolean('ativo')->default('true');
            $table->integer('area_id');
            $table->foreign('area_id')->references('id')->on('areas'); 
            $table->integer('funcionario_id');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios'); 
            $table->string('nome', 50)->unique();
            $table->text('descricao')->nullable();
            $table->boolean('ticket')->default('false');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departamentos');
    }
}
