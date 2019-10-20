<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewPublicacoesBusca extends Migration
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
                    setweight(to_tsvector('portuguese',coalesce((string_agg(sem_acento(pt.tag), ' ')), '')),'B') || 
                    setweight(to_tsvector('portuguese',sem_acento(p.resumo)),'C') || 
                    setweight(to_tsvector('portuguese',coalesce((string_agg(sem_acento(pc.conteudo), ' ')), '')),'D') 
                ) as documento, 


                p.id, p.titulo, p.resumo,
                coalesce((string_agg(pt.tag, ','))) as tag, 
                coalesce((string_agg(pc.conteudo, ' '))) as conteudo

                FROM 
                publicacoes p LEFT JOIN 
                publicacoes_tags pt ON pt.publicacao_id = p.id LEFT JOIN 
                publicacoes_conteudos pc ON pc.publicacao_id = p.id AND pc.publicacao_conteudo_tipo_id IN (SELECT id FROM publicacoes_conteudos_tipos WHERE nome = 'TEXTO') 

                GROUP BY p.id 
        ");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW publicacoes_busca");
    }
}
