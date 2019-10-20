<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();           
            $table->integer('usuario_inclusao_id')->unsigned()->nullable();
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->integer('usuario_alteracao_id')->unsigned()->nullable();
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
            $table->boolean('ativo')->default('true');
            $table->integer('funcionario_id')->unsigned()->nullable();
            $table->foreign('funcionario_id')->references('id')->on('funcionarios'); 
            $table->integer('perfil_id')->unsigned()->nullable();            
            $table->string('nome', 100)->unique();
            $table->string('password');
            $table->string('usuario', 50)->unique();
            $table->rememberToken();
            $table->string('api_token', 100)->unique()->nullable();
        });

        Schema::table('funcionarios', function($table) {
            $table->foreign('usuario_inclusao_id')->references('id')->on('usuarios');
            $table->foreign('usuario_alteracao_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funcionarios', function(Blueprint $table) {
            $table->dropForeign(['usuario_inclusao_id']);
            $table->dropForeign(['usuario_alteracao_id']);
        });
        Schema::dropIfExists('usuarios');
    }
}
