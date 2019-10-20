<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PublicacoesVisualizacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('publicacoes_visualizacoes', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
          
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');

            $table->integer('publicacao_id')->unsigned()->nullable();
            $table->foreign('publicacao_id')->references('id')->on('publicacoes');

          
            $table->ipAddress('ip');
            $table->text('pagina_anterior');
            $table->string('browser', 100);
            $table->string('so', 200);
       

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicacoes_visualizacoes');
    }



}
