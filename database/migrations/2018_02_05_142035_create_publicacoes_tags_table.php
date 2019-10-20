<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacoesTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicacoes_tags', function (Blueprint $table) {
        
            $table->increments('id');

            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
          
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
                          
            $table->integer('publicacao_id')->unsigned()->nullable();
            $table->foreign('publicacao_id')->references('id')->on('publicacoes');

            $table->string('tag', 50);
        });

         DB::statement("CREATE UNIQUE INDEX Publicacao_tag_unique ON publicacoes_tags (tag, publicacao_id);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicacoes_tags');
    }
}
