<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('funcionarios', function ($table) {
            $table->integer('cargo_id')->unsigned()->nullable();
            $table->foreign('cargo_id')->references('id')->on('cargos');
            $table->integer('gestor_id')->unsigned()->nullable();
            $table->foreign('gestor_id')->references('id')->on('funcionarios');
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     
        Schema::table('funcionarios', function(Blueprint $table) {
            $table->dropForeign(['cargo_id']);
            $table->dropColumn('cargo_id');
            $table->dropForeign(['gestor_id']);
            $table->dropColumn('gestor_id');

        });

    }
}
