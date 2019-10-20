<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewPermissaoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW permissao_usuario AS
            SELECT
                u.id AS usuario_id,
                u.usuario AS usuario_login,
                pf.id AS perfil_id, 
                pf.nome AS perfil_nome,
                pm.id AS permissao_id,
                pm.descricao AS permissao_descricao,
                pm.identificador AS permissao_identificador,
                m.id AS menu_id,
                m.menu_id AS heranca,
                EXISTS( SELECT perfis.id FROM perfis
            INNER JOIN acesso_perfil ON acesso_perfil.perfil_id = perfis.id
            INNER JOIN acessos ON acessos.id = acesso_perfil.acesso_id
            INNER JOIN acesso_permissao ON acesso_permissao.acesso_id = acessos.id
            INNER JOIN permissoes ON permissoes.id = acesso_permissao.permissao_id 
            INNER JOIN menus ON menus.id = permissoes.menu_id 
            WHERE perfis.id=pf.id AND permissoes.id = pm.id ) AS permissao                
            FROM usuarios u
            INNER JOIN perfis pf ON pf.id = u.perfil_id
            LEFT JOIN permissoes pm on true
            LEFT JOIN menus m ON m.id = pm.menu_id 
            GROUP BY usuario_id, usuario_login, pf.id, perfil_nome, permissao_id, permissao_descricao, permissao_identificador, m.id, heranca, permissao;
        ");
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW permissao_usuario");
    }
}
