<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollunMonitoramentoServidoresColetasSprint031 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramento_servidores_coletas', function (Blueprint $table) {                     
            $table->boolean('coleta_manual')->default(false);
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("monitoramento_servidores_coletas", function ($table) {
            $table->dropColumn('coleta_manual');
            $table->dropForeign(['usuario_inclusao_id']);
            $table->dropColumn('usuario_inclusao_id');
        });
    }
}
