<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumPublicacaoHistoricoBusca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::table('publicacoes_buscas_historicos', function (Blueprint $table) {
            $table->integer('pagina')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('publicacoes_buscas_historicos', function (Blueprint $table) {
            $table->dropColumn('pagina');
        });
    }
}
