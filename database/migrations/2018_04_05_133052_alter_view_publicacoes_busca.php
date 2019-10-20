<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewPublicacoesBusca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
              CREATE OR REPLACE VIEW publicacoes_busca AS 
                SELECT 
                (
                    setweight(to_tsvector('portuguese',sem_acento(p.titulo)),'A') || 
                    setweight(to_tsvector('portuguese',coalesce((string_agg(DISTINCT sem_acento(pt.tag), ' ')), '')),'B') || 
                    setweight(to_tsvector('portuguese',sem_acento(p.resumo)),'C') || 
                    setweight(to_tsvector('portuguese',coalesce((string_agg(DISTINCT sem_acento(pc.conteudo), ' ')), '')),'D') || 
                    setweight(to_tsvector('portuguese',coalesce((string_agg(DISTINCT sem_acento(f.nome), ',')), '')),'D') 
                ) as documento, 

                p.id, p.titulo, p.resumo,
                coalesce((string_agg(DISTINCT pt.tag, ','))) as tag, 
                coalesce((string_agg(DISTINCT pc.conteudo, ' '))) as conteudo, 
                coalesce((string_agg(DISTINCT f.nome, ','))) as colaboradores 

                FROM 
                publicacoes p LEFT JOIN 
                publicacoes_tags pt ON pt.publicacao_id = p.id LEFT JOIN 
                publicacoes_conteudos pc ON pc.publicacao_id = p.id AND pc.publicacao_conteudo_tipo_id IN (SELECT id FROM publicacoes_conteudos_tipos WHERE nome = 'TEXTO') LEFT JOIN 
                publicacoes_colaboradores pco ON pco.publicacao_id = p.id LEFT JOIN 
                funcionarios f ON pco.funcionario_id = f.id 

                GROUP BY p.id; 
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
