<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVersaoSprint015 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         *  Alterar tambem na seed ParametrosTableSeeder para a versao atual do sistema.
        */
        DB::table('parametros')->where('nome','VERSAO')->update([
            'valor_texto' => '1.0.01'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('parametros')->where('nome','VERSAO')->update([
            'valor_texto' => '1.0.00'
        ]);
    }
}
