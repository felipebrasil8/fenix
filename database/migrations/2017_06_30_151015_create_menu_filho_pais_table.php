<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuFilhoPaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
                CREATE OR REPLACE FUNCTION menu_filho_pai( permissao_id_menu INT )
                    RETURNS SETOF RECORD
                    AS $$
                BEGIN
                    RETURN QUERY WITH RECURSIVE menu_filho_pai(id, menu_id, nome, icone, url, prioridade) AS (
                      SELECT m.id, m.menu_id, m.nome, m.icone, m.url, 1 as prioridade FROM menus m WHERE m.id = permissao_id_menu
                      UNION
                      SELECT menus.id, menus.menu_id, menus.nome, menus.icone, menus.url, menu_filho_pai.prioridade+1 as prioridade FROM menus INNER JOIN menu_filho_pai ON menus.id = menu_filho_pai.menu_id)
                    SELECT id, menu_id, CAST(nome AS text), CAST(icone AS text), CAST(url AS text), CAST(prioridade AS integer) FROM menu_filho_pai ORDER BY prioridade;
                    RETURN;
                END;
                $$ LANGUAGE 'plpgsql';
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP FUNCTION menu_filho_pai(integer);");
    }
}
