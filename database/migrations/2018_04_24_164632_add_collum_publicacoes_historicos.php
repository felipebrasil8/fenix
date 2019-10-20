<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumPublicacoesHistoricos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('publicacoes_historicos', function (Blueprint $table) {
            $table->timestamp('updated_at')->useCurrent()->unsigned()->nullable();
            $table->softDeletes();
            $table->integer('usuario_exclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
        });
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table("publicacoes_historicos", function ($table) {
            $table->dropColumn('updated_at');
            $table->dropSoftDeletes();
            $table->dropForeign(['usuario_exclusao_id']);
            $table->dropColumn('usuario_exclusao_id');
        });
        
    }
}
