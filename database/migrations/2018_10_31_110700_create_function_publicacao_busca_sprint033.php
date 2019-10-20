<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionPublicacaoBuscaSprint033 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION  function_atualiza_busca_publicacao() RETURNS trigger LANGUAGE plpgsql AS 
            $$

            DECLARE
                p_id int;
            BEGIN

                IF TG_TABLE_NAME = 'publicacoes' THEN
                    p_id := NEW.id ;
                ELSE
                    p_id := NEW.publicacao_id ;
                END IF;
                
                DELETE FROM publicacoes_busca WHERE id = p_id; 

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
                    WHERE p.id = p_id
                    GROUP BY p.id
    
                ;

                RETURN NEW;
            END;

            $$;
        ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP FUNCTION function_atualiza_busca_publicacao();");
    }
}