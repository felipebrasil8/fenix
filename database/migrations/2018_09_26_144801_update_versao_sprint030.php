<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVersaoSprint030 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	DB::table('parametros')->where('nome','VERSAO')->update([
		'valor_texto' => '1.0.10'
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
		'valor_texto' => '1.0.09'
	]);
    }
}
