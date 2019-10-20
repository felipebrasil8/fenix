<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacoesCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicacoes_categorias', function (Blueprint $table) {
            $table->increments('id');
            
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->useCurrent()->unsigned()->nullable();
            $table->softDeletes();
          
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
          
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');

            $table->integer('publicacao_categoria_id')->unsigned()->nullable();
            $table->foreign('publicacao_categoria_id')->references('id')->on('publicacoes_categorias');

            $table->string('nome', 100);
            $table->text('descricao');
            $table->string('icone', 50)->default('angle-double-right');
            $table->integer('ordem')->default(0);

        });

         DB::statement("CREATE UNIQUE INDEX Categoria_nome_categoria_id_unique ON publicacoes_categorias (nome, COALESCE(publicacao_categoria_id, 0));");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicacoes_categorias');
    }
}
