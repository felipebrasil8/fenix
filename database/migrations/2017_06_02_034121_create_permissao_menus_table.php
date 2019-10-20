<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissaoMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW permissao_menu AS
            SELECT * FROM ( 
              SELECT 
                    m.id, 
                    m.nome, 
                    m.descricao, 
                    m.icone, 
                    m.url, 
                    m.nivel, 
                    m.ordem, 
                    m.menu_id AS menu_pai, 
                    pu.usuario_id 
                FROM menus m
                LEFT JOIN permissao_usuario pu ON (pu.heranca = m.id OR pu.menu_id = m.id) AND permissao
                GROUP BY m.id, m.nome, m.descricao, m.icone, m.url, m.nivel,  m.ordem, menu_pai, usuario_id
            ) tb 
            ORDER BY nivel, ordem 
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW permissao_menu");
    }
}
