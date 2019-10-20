<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoliticaSenhasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('politica_senhas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_id');
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->boolean('ativo')->default('true');
            $table->integer('tamanho_minimo')->unsigned()->default(8);
            $table->integer('qtde_minima_letras')->unsigned()->default(1);
            $table->integer('qtde_minima_numeros')->unsigned()->default(1);
            $table->integer('qtde_minima_especial')->unsigned()->default(1);
            $table->string('caractere_especial', 20)->default('!@#$%&*?+=');
            $table->boolean('maiusculo_minusculo')->default('false');
            $table->integer('usuario_exclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->DateTime('dt_exclusao')->nullable();
        });     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('politica_senhas');
    }
}
