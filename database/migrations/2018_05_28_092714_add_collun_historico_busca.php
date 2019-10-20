<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollunHistoricoBusca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publicacoes_buscas_historicos', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->unsigned()->nullable();
            $table->boolean('tratada')->default('false');
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
        });
    }

    /**tratada
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("publicacoes_buscas_historicos", function ($table) {
            $table->dropColumn('updated_at');
            $table->dropColumn('tratada');
           
            $table->dropForeign(['usuario_alteracao_id']);
            $table->dropColumn('usuario_alteracao_id');
        });
    }
}
