<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMonitoramentoServidoresItensSprint031 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramento_servidores_itens', function (Blueprint $table) {                     
            $table->string('chamado_vinculado')->nullable();
            $table->timestamp('chamado_vinculado_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_chamado_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_chamado_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("monitoramento_servidores_itens", function ($table) {
            $table->dropColumn('chamado_vinculado');
            $table->dropColumn('chamado_vinculado_at');
            $table->dropForeign(['usuario_inclusao_chamado_id']);
            $table->dropColumn('usuario_inclusao_chamado_id');
        });
    }
}
