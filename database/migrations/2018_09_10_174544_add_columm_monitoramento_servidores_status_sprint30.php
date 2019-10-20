<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColummMonitoramentoServidoresStatusSprint30 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monitoramento_servidores_status', function (Blueprint $table) {                     
            $table->boolean('filtro_servidor')->nullable();
            $table->boolean('filtro_item')->nullable();
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table("monitoramento_servidores_status", function ($table) {
            $table->dropColumn('filtro_servidor');
            $table->dropColumn('filtro_item');
          
        });
    }
}
