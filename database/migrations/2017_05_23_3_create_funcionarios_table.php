<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable(); // nullable permite null
            $table->integer('usuario_alteracao_id')->unsigned()->nullable(); // nullable permite null
            $table->boolean('ativo')->default('true');
            $table->string('nome', 50)->unique();
            $table->string('nome_completo', 100)->unique();
            $table->string('email',50)->unique();
            $table->date('dt_nascimento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('funcionarios');
    }
}
