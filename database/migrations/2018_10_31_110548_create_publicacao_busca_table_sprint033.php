<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacaoBuscaTableSprint033 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW publicacoes_busca");

        DB::statement("
            CREATE TABLE publicacoes_busca (
                id int UNIQUE,
                created_at timestamp DEFAULT now(),
                documento tsvector
            );
        ");


         DB::statement("
            INSERT INTO publicacoes_busca
            ( documento, id ) 
            SELECT 
                (
                   (
                    setweight(to_tsvector('portuguese'::regconfig, sem_acento(p.titulo::text)), 'A') || 
                    setweight(to_tsvector('portuguese'::regconfig, COALESCE(string_agg(DISTINCT(sem_acento(pt.tag)::text), ' '::text), ''::text)), 'B')) || 
                    setweight(to_tsvector('portuguese'::regconfig, sem_acento(p.resumo)), 'C')) || 
                    setweight(to_tsvector('portuguese'::regconfig, COALESCE(string_agg(DISTINCT(sem_acento(pc.conteudo)), ' '::text), ''::text)), 'D'
                    ) AS documento,
                    p.id
                FROM publicacoes p
                LEFT JOIN publicacoes_tags pt ON pt.publicacao_id = p.id AND pt.rascunho = false
                LEFT JOIN publicacoes_conteudos pc ON pc.publicacao_id = p.id AND pc.rascunho = false 
                    AND (pc.publicacao_conteudo_tipo_id IN ( 
                        SELECT 
                            publicacoes_conteudos_tipos.id 
                        FROM 
                            publicacoes_conteudos_tipos
                        WHERE 
                            publicacoes_conteudos_tipos.nome::text = 'TEXTO'::text )
                    )
            GROUP BY p.id
        ");
        
        Schema::table('publicacoes_busca', function (Blueprint $table) {
            $table->foreign('id')->references('id')->on('publicacoes');
        });
   
        DB::statement("
            CREATE INDEX publicacoes_busca_documento_idx01 ON publicacoes_busca USING gist(documento);
        ");
         DB::statement("
            CREATE INDEX publicacoes_visualizacoes_idx01 ON publicacoes_visualizacoes (publicacao_id,usuario_inclusao_id);
        ");

    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            DROP INDEX publicacoes_busca_documento_idx01;
        ");

        Schema::dropIfExists('publicacoes_busca');
    
        DB::statement("
            CREATE OR REPLACE VIEW publicacoes_busca AS 
                SELECT 
                    ((setweight(to_tsvector('portuguese'::regconfig, sem_acento(p.titulo::text)), 'A') || setweight(to_tsvector('portuguese'::regconfig, COALESCE(string_agg(sem_acento(pt.tag::text), ' '::text), ''::text)), 'B')) || setweight(to_tsvector('portuguese'::regconfig, sem_acento(p.resumo)), 'C')) || setweight(to_tsvector('portuguese'::regconfig, COALESCE(string_agg(sem_acento(pc.conteudo), ' '::text), ''::text)), 'D') AS documento,
                    p.id,
                    p.titulo,
                    p.resumo,
                    COALESCE(string_agg(pt.tag::text, ','::text)) AS tag,
                    COALESCE(string_agg(pc.conteudo, ' '::text)) AS conteudo
                FROM publicacoes p
                LEFT JOIN publicacoes_tags pt ON pt.publicacao_id = p.id AND pt.rascunho = false
                LEFT JOIN publicacoes_conteudos pc ON pc.publicacao_id = p.id AND pc.rascunho = false 
                    AND (pc.publicacao_conteudo_tipo_id IN ( 
                        SELECT 
                            publicacoes_conteudos_tipos.id 
                        FROM 
                            publicacoes_conteudos_tipos
                        WHERE 
                            publicacoes_conteudos_tipos.nome::text = 'TEXTO'::text )
                    )
              GROUP BY p.id;
        ");

    }
}