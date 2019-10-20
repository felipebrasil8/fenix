<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonitoramentoServidoresStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('monitoramento_servidores_status', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->softDeletes();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('usuario_exclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_exclusao_id')->references('id')->on('usuarios');
            $table->string('nome', 50)->unique();
            $table->string('identificador', 50)->unique();

            $table->boolean('alarme');   
            $table->integer('peso')->nullable();
            $table->string('cor', 10);
            $table->string('icone', 50);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoramento_servidores_status');
    }
}
