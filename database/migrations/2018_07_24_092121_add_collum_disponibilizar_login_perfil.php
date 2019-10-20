<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumDisponibilizarLoginPerfil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perfis', function (Blueprint $table) {            
            $table->boolean('todos_dias')->nullable()->default('true');           
            $table->time('horario_inicial')->nullable()->default('00:00:00');           
            $table->time('horario_final')->nullable()->default('23:59:59');           
            $table->text('configuracao_de_rede')->nullable();           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("perfis", function ($table) {
            $table->dropColumn('todos_dias');                
            $table->dropColumn('horario_inicial');            
            $table->dropColumn('horario_final');            
            $table->dropColumn('configuracao_de_rede');            
        });
    }
}
