<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametros', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('created_at')->useCurrent()->unsigned()->nullable();
            $table->timestamp('updated_at')->unsigned()->nullable();
            $table->integer('usuario_inclusao_id')->unsigned()->nullable(); // nullable permite null
            $table->integer('usuario_alteracao_id')->unsigned()->nullable(); // nullable permite null
            $table->boolean('ativo')->default('true');
            $table->integer('parametro_grupo_id');
            $table->foreign('parametro_grupo_id')->references('id')->on('parametros_grupo');
            $table->integer('parametro_tipo_id');
            $table->foreign('parametro_tipo_id')->references('id')->on('parametros_tipo');
            $table->string('nome', 50)->unique();   
            $table->text('descricao');
            $table->text('valor_texto')->nullable(); // permite null
            $table->integer('valor_numero')->nullable(); // permite null
            $table->boolean('valor_booleano')->nullable(); // permite null
            $table->integer('ordem');
            $table->boolean('editar')->default('true');
            $table->boolean('obrigatorio')->default('true');         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parametros');
    }
}
