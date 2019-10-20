<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacoesRecomendacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicacoes_recomendacoes', function (Blueprint $table) {

            $table->increments('id');

            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();

            $table->integer('usuario_inclusao_id')->unsigned();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');

            $table->integer('usuario_recomendado_id')->unsigned();
            $table->foreign('usuario_recomendado_id')->references('id')->on('usuarios');

            $table->integer('publicacao_id')->unsigned();
            $table->foreign('publicacao_id')->references('id')->on('publicacoes');

            $table->string('mensagem', 100);

            $table->boolean('visualizada')->default('false'); 
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicacoes_recomendacoes');
    }
}
