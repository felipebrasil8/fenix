<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDescricaoToAcaoTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets_acao', function (Blueprint $table) {
            $table->text('descricao')->nullable();
        });

         DB::statement("CREATE UNIQUE INDEX tickets_acao_nome_departamento_id_unique ON tickets_acao (nome, departamento_id);");
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    
    }
}
