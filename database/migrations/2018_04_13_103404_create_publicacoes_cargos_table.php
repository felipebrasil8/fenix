<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacoesCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicacoes_cargos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('cargo_id')->unsigned();
            $table->foreign('cargo_id')->references('id')->on('cargos');          
            $table->integer('publicacao_id')->unsigned();
            $table->foreign('publicacao_id')->references('id')->on('publicacoes');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
        });

        DB::statement("CREATE UNIQUE INDEX publicacoes_cargos_unique ON publicacoes_cargos USING btree (publicacao_id, cargo_id);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicacoes_cargos');
    }
}
