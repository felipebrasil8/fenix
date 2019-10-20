<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublicacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {   
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->dropColumn('dt_publicacao');
            $table->dropColumn('dt_ultima_atualizacao');
           
        });
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->date('dt_publicacao')->nullable();
            $table->date('dt_ultima_atualizacao')->nullable();
            $table->date('dt_desativacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('publicacoes', function (Blueprint $table) {
            $table->dropColumn('dt_publicacao');
            $table->dropColumn('dt_ultima_atualizacao');
            $table->dropColumn('dt_desativacao');
        });
    }
}


