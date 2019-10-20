<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVersaoSprint020 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       	DB::table('parametros')->where('nome','VERSAO')->update([
		'valor_texto' => '1.0.04'
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
		'valor_texto' => '1.0.03'
	]);
    }
}
