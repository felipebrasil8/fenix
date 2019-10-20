<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceViewDadosPublicacaoSprint024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS dados_publicacao_view;");

        DB::statement("CREATE OR REPLACE VIEW dados_publicacao_view AS
            
            SELECT

                p.id,
                pc.nome AS categoria, 
                ( CASE WHEN pcp.nome IS NOT NULL THEN pcp.nome ELSE '-' END) AS categoria_pai, 
                p.titulo, 
                p.resumo, 
                p.dt_publicacao as data_publicacao, 
                p.dt_ultima_atualizacao as data_ultima_atualizacao, 
                p.dt_desativacao as data_desativacao,                

                ( SELECT string_agg( DISTINCT f.nome , ';' ORDER BY f.nome) FROM publicacoes_colaboradores pco INNER JOIN funcionarios f on f.id = pco.funcionario_id WHERE pco.publicacao_id = p.id ) AS colaboradores,

                ( SELECT string_agg( DISTINCT pt.tag , ';' ORDER BY pt.tag) FROM publicacoes_tags pt  WHERE pt.publicacao_id = p.id ) AS tags, 

                ( SELECT string_agg(to_char(novo::DATE, 'DD/MM/YYYY'), ';') FROM (SELECT  DISTINCT (movimentacao->>'novo')::DATE as novo FROM publicacoes_historicos WHERE alteracao = 'ATUALIZACAO' AND movimentacao->>'novo' <> '' AND publicacao_id = p.id ORDER BY novo ) as tb ) as datas_atualizacao,

                ( CASE WHEN p.restricao_acesso is true THEN ( SELECT string_agg( DISTINCT c.nome, ';' ORDER BY c.nome) FROM publicacoes_cargos pc INNER JOIN cargos c ON c.id = pc.cargo_id WHERE pc.publicacao_id = p.id ) ELSE 'NÃO' END 
                ) as restricao_acesso,

                lista_relacionados AS qtde_publicacoes_relacionadas,

                (SELECT COUNT( pf.usuario_id ) FROM publicacoes_favoritos pf WHERE pf.publicacao_id = p.id) AS qtde_favoritos,

                (SELECT COUNT(pv.usuario_inclusao_id) FROM publicacoes_visualizacoes pv WHERE pv.publicacao_id = p.id ) AS qtde_acessos_total, 

                (SELECT COUNT(pv.usuario_inclusao_id) FROM publicacoes_visualizacoes pv WHERE pv.created_at::DATE >= CURRENT_DATE - 30 AND pv.publicacao_id = p.id ) AS qtde_acessos_ultimos_trinta_dias, 

                (SELECT COUNT(*) FROM (SELECT COUNT( pv.usuario_inclusao_id) FROM publicacoes_visualizacoes pv WHERE pv.publicacao_id = p.id GROUP BY pv.usuario_inclusao_id) tb) AS qtde_de_usuarios_que_acessou, 

                (SELECT COUNT(*) FROM (SELECT COUNT( pv.usuario_inclusao_id) FROM publicacoes_visualizacoes pv WHERE pv.created_at::DATE >= CURRENT_DATE - 30 AND pv.publicacao_id = p.id GROUP BY pv.usuario_inclusao_id) tb) AS qtde_de_usuarios_que_acessou_ultimos_trinta_dias, 

                ui.nome AS usuario_inclusao, 

                p.created_at::DATE AS data_inclusao,

                p.created_at::TIME AS hora_inclusao,

                ua.nome AS usuario_ultima_alteracao,

                p.updated_at::DATE AS data_ultima_alteracao,

                p.updated_at::TIME AS hora_ultima_alteracao,

                (SELECT COUNT(pr.id) FROM publicacoes_recomendacoes pr WHERE pr.publicacao_id = p.id ) AS qtde_recomendacoes_total

            FROM publicacoes p

            INNER JOIN usuarios ui on ui.id = p.usuario_inclusao_id 
            INNER JOIN usuarios ua on ua.id = p.usuario_alteracao_id 
            LEFT JOIN publicacoes_categorias pc on p.publicacao_categoria_id = pc.id 
            LEFT JOIN publicacoes_categorias pcp on pc.publicacao_categoria_id = pcp.id 
            ORDER BY p.id asc

        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS dados_publicacao_view;");

        DB::statement("CREATE OR REPLACE VIEW dados_publicacao_view AS
            
            SELECT

                p.id,
                pc.nome AS categoria, 
                ( CASE WHEN pcp.nome IS NOT NULL THEN pcp.nome ELSE '-' END) AS categoria_pai, 
                p.titulo, 
                p.resumo, 
                p.dt_publicacao as data_publicacao, 
                p.dt_ultima_atualizacao as data_ultima_atualizacao, 
                p.dt_desativacao as data_desativacao,                

                ( SELECT string_agg( DISTINCT f.nome , ';' ORDER BY f.nome) FROM publicacoes_colaboradores pco INNER JOIN funcionarios f on f.id = pco.funcionario_id WHERE pco.publicacao_id = p.id ) AS colaboradores,

                ( SELECT string_agg( DISTINCT pt.tag , ';' ORDER BY pt.tag) FROM publicacoes_tags pt  WHERE pt.publicacao_id = p.id ) AS tags, 

                ( SELECT string_agg(to_char(novo::DATE, 'DD/MM/YYYY'), ';') FROM (SELECT  DISTINCT (movimentacao->>'novo')::DATE as novo FROM publicacoes_historicos WHERE alteracao = 'ATUALIZACAO' AND movimentacao->>'novo' <> '' AND publicacao_id = p.id ORDER BY novo ) as tb ) as datas_atualizacao,

                ( CASE WHEN p.restricao_acesso is true THEN ( SELECT string_agg( DISTINCT c.nome, ';' ORDER BY c.nome) FROM publicacoes_cargos pc INNER JOIN cargos c ON c.id = pc.cargo_id WHERE pc.publicacao_id = p.id ) ELSE 'NÃO' END 
                ) as restricao_acesso,

                lista_relacionados AS qtde_publicacoes_relacionadas,

                (SELECT COUNT( pf.usuario_id ) FROM publicacoes_favoritos pf WHERE pf.publicacao_id = p.id) AS qtde_favoritos,

                (SELECT COUNT(pv.usuario_inclusao_id) FROM publicacoes_visualizacoes pv WHERE pv.publicacao_id = p.id ) AS qtde_acessos_total, 

                (SELECT COUNT(pv.usuario_inclusao_id) FROM publicacoes_visualizacoes pv WHERE pv.created_at::DATE >= CURRENT_DATE - 30 AND pv.publicacao_id = p.id ) AS qtde_acessos_ultimos_trinta_dias, 

                (SELECT COUNT(*) FROM (SELECT COUNT( pv.usuario_inclusao_id) FROM publicacoes_visualizacoes pv WHERE pv.publicacao_id = p.id GROUP BY pv.usuario_inclusao_id) tb) AS qtde_de_usuarios_que_acessou, 

                (SELECT COUNT(*) FROM (SELECT COUNT( pv.usuario_inclusao_id) FROM publicacoes_visualizacoes pv WHERE pv.created_at::DATE >= CURRENT_DATE - 30 AND pv.publicacao_id = p.id GROUP BY pv.usuario_inclusao_id) tb) AS qtde_de_usuarios_que_acessou_ultimos_trinta_dias, 

                ui.nome AS usuario_inclusao, 

                p.created_at::DATE AS data_inclusao,

                p.created_at::TIME AS hora_inclusao,

                ua.nome AS usuario_ultima_alteracao,

                p.updated_at::DATE AS data_ultima_alteracao,

                p.updated_at::TIME AS hora_ultima_alteracao

            FROM publicacoes p

            INNER JOIN usuarios ui on ui.id = p.usuario_inclusao_id 
            INNER JOIN usuarios ua on ua.id = p.usuario_alteracao_id 
            LEFT JOIN publicacoes_categorias pc on p.publicacao_categoria_id = pc.id 
            LEFT JOIN publicacoes_categorias pcp on pc.publicacao_categoria_id = pcp.id 
            ORDER BY p.id asc

        ");
    }
}
