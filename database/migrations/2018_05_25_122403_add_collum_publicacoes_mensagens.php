<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumPublicacoesMensagens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publicacoes_mensagens', function (Blueprint $table) {
            $table->boolean('respondida')->default(false);
            $table->text('resposta')->nullable();
            $table->timestamp('dt_resposta')->unsigned()->nullable();
            $table->integer('usuario_resposta_id')->unsigned()->nullable();
            $table->foreign('usuario_resposta_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("publicacoes_mensagens", function ($table) {
            $table->dropColumn('respondida');
            $table->dropColumn('resposta');
            $table->dropColumn('dt_resposta');
            $table->dropForeign(['usuario_resposta_id']);
            $table->dropColumn('usuario_resposta_id');
        });
    }

}



