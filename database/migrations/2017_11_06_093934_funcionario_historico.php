<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FuncionarioHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('funcionarios_historico', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->integer('funcionario_id');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios'); 
            $table->integer('usuario_inclusao_id')->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios'); 
            $table->json('alteracao');
          
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funcionarios_historico', function(Blueprint $table) {
            $table->dropForeign(['funcionario_id']);
            $table->dropColumn('funcionario_id');
            $table->dropForeign(['usuario_inclusao_id']);
            $table->dropColumn('usuario_inclusao_id');
        });

        Schema::dropIfExists('funcionarios_historico');
    }
}
