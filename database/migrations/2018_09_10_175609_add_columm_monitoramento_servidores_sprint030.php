<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColummMonitoramentoServidoresSprint030 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramento_servidores', function (Blueprint $table) {                     
            $table->text('ip')->nullable();
            $table->text('dns')->nullable();
            $table->text('versao')->nullable();
            $table->text('endereco')->nullable();
            $table->text('bairro')->nullable();
            $table->text('cidade')->nullable();
            $table->text('estado')->nullable();
            $table->text('status')->nullable();
            $table->text('plano')->nullable();
            $table->text('grupo')->nullable();
            $table->text('plano_tipo')->nullable();
            $table->text('cliente')->nullable();
            $table->text('razao_social')->nullable();
            $table->json('configuracao')->nullable();
            $table->text('mensagem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("monitoramento_servidores", function ($table) {
            $table->dropColumn('ip');
            $table->dropColumn('dns');
            $table->dropColumn('versao');
            $table->dropColumn('endereco');
            $table->dropColumn('bairro');
            $table->dropColumn('cidade');
            $table->dropColumn('estado');
            $table->dropColumn('status');
            $table->dropColumn('plano');
            $table->dropColumn('grupo');
            $table->dropColumn('plano_tipo');
            $table->dropColumn('cliente');
            $table->dropColumn('razao_social');
            $table->dropColumn('configuracao');
            $table->dropColumn('mensagem');
        });
    }
}
