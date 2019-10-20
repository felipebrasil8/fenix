<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_login', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->integer('usuario_id')->unsigned()->nullable();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->string('usuario', 50);//nome de usuario relacionado com fk_usuario
            $table->integer('perfil_id')->unsigned()->nullable();
            $table->foreign('perfil_id')->references('id')->on('perfis');
            $table->string('perfil', 50);//nome de perfil relacionado com fk_perfil
            $table->json('credencial');//credencial que usou para logar(ex:fulano.ciclano)
            $table->string('ip', 15);
            $table->enum('tipo', ['LOGIN', 'LOGOUT', 'FALHA']);
            $table->text('mensagem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_login');
    }
}
