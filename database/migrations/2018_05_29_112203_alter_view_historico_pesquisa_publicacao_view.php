<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewHistoricoPesquisaPublicacaoView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        DB::statement("DROP VIEW historico_pesquisa_publicacao_view;");

        DB::statement("CREATE OR REPLACE VIEW historico_pesquisa_publicacao_view AS

            SELECT 

                pbh.id as id, 
                pbh.created_at::date as data, 
                pbh.created_at::time as horario, 
                u.nome, 
                COALESCE(d.nome, '-') as departamento, 
                COALESCE(a.nome, '-') as area, 
                pbh.busca, 
                pbh.ip, 
                pbh.qtde_resultados as qtde_resultados, 
                (CASE WHEN pbh.qtde_resultados > 0 THEN '-'
                        WHEN pbh.tratada = false THEN 'NÃO'
                            ELSE 'SIM'
                                END) as tratada,
                pbh.pagina as pagina, 
                pbh.resultados,
                pbh.created_at



            FROM publicacoes_buscas_historicos pbh 
            
            INNER JOIN usuarios      u ON u.id = pbh.usuario_id 
            LEFT JOIN funcionarios  f ON f.id = u.funcionario_id
            LEFT JOIN cargos        c ON c.id = f.cargo_id
            LEFT JOIN departamentos d ON d.id = c.departamento_id
            LEFT JOIN areas          a ON a.id = d.area_id
            ORDER BY pbh.created_at DESC
  
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW historico_pesquisa_publicacao_view;");
        
        DB::statement("CREATE OR REPLACE VIEW historico_pesquisa_publicacao_view AS

            SELECT 

                pbh.id as id, 
                pbh.created_at::date as data, 
                pbh.created_at::time as horario, 
                u.nome, 
                COALESCE(d.nome, '-') as departamento, 
                COALESCE(a.nome, '-') as area, 
                pbh.busca, 
                pbh.ip, 
                pbh.qtde_resultados as qtde_resultados, 
                pbh.pagina as pagina, 
                pbh.resultados,
                pbh.created_at

            FROM publicacoes_buscas_historicos pbh 
            
            INNER JOIN usuarios      u ON u.id = pbh.usuario_id 
            LEFT JOIN funcionarios  f ON f.id = u.funcionario_id
            LEFT JOIN cargos        c ON c.id = f.cargo_id
            LEFT JOIN departamentos d ON d.id = c.departamento_id
            LEFT JOIN areas          a ON a.id = d.area_id
            ORDER BY pbh.created_at DESC
  
        ");
    }
}
