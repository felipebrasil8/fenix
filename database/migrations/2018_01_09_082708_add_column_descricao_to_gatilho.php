<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDescricaoToGatilho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        


	Schema::table('tickets_gatilho', function (Blueprint $table) {
        $table->text('descricao')->nullable();
        $table->softDeletes();

    });
    

    Schema::table('tickets_gatilho', function (Blueprint $table) {
    
        $table->dropUnique('tickets_gatilho_nome_unique');
  
    });


    DB::statement("CREATE UNIQUE INDEX tickets_gatilho_nome_departamento_id_unique ON tickets_gatilho (nome, departamento_id);");
        


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
