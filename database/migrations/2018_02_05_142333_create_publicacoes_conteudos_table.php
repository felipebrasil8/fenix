<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacoesConteudosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicacoes_conteudos', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->useCurrent()->unsigned()->nullable();

            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');

            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
          
    
            $table->softDeletes();

            $table->integer('publicacao_id')->unsigned()->nullable();
            $table->foreign('publicacao_id')->references('id')->on('publicacoes');

            $table->integer('publicacao_conteudo_tipo_id')->unsigned()->nullable();
            $table->foreign('publicacao_conteudo_tipo_id')->references('id')->on('publicacoes_conteudos_tipos');

            $table->json('dados')->nullable();
            $table->text('conteudo')->nullable();
            $table->text('adicional')->nullable();
            $table->integer('ordem')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicacoes_conteudos');
    }
}
