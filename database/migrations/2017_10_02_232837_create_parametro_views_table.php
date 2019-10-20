<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametroViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW parametro_view AS
            SELECT
                p.id,
                p.nome as nome, 
                p.descricao as descricao,
                p.valor_texto,
                p.valor_numero,
                p.valor_booleano,
                p.parametro_tipo_id,
                p.parametro_grupo_id,
                p.editar,
                p.ativo ,
                p.ordem,
                pg.nome as grupo_nome, 
                pt.nome as tipo_nome  
            FROM parametros p            
            LEFT JOIN parametros_grupo pg on pg.id = p.parametro_grupo_id
            LEFT JOIN parametros_tipo pt ON pt.id = p.parametro_tipo_id ;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW parametro_view");
    }
}
