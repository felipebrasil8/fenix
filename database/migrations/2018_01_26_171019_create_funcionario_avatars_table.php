<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncionarioAvatarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionarios_avatars', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->integer('funcionario_id');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios');
            $table->integer('usuario_inclusao_id')->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->text('avatar_grande')->nullable();
            $table->text('avatar_pequeno')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('funcionarios_avatars');
    }
}
