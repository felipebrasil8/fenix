<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMonitoramentoServidoresSprint031 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramento_servidores', function (Blueprint $table) {
            $table->boolean('executa_ping')->default(true);
            $table->boolean('executa_porta')->default(true);
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
            $table->dropColumn('executa_ping');
            $table->dropColumn('executa_porta');
        });
    }
}
