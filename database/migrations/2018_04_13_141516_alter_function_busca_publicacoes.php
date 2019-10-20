<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFunctionBuscaPublicacoes extends Migration
{
    /**
     * Run the migrations.
     * Alterada a função para novas validações
     * 1º - Converte \n, \t, & e |  para um espaço em branco
     * 2º - Converte mais de um espaço seguido para um espaço em branco
     * 3º - Remove (se houver) o espaço do início e do fim da string
     * 4º - Troca os espaços por & ou | para a busca
     * 5º - Troca caracteres com acento por sem acento
     *
     * @return void
     */
    public function up()
    {
        DB::statement(

            "
            CREATE OR REPLACE FUNCTION formata_tsquery( busca TEXT, busca_e boolean ) RETURNS tsquery
                LANGUAGE plpgsql
                AS $$    
                            
                BEGIN
                    
                    IF ( busca_e = TRUE) THEN
                   
                        RETURN to_tsquery( 'portuguese' , 
                            sem_acento( 
                                replace( 
                                    trim(both ' ' from 
                                        REGEXP_REPLACE( 
                                            REGEXP_REPLACE( 
                                                busca
                                            , '[^a-zà-úA-ZÀ-Ú0-9\-+]', ' ', 'g'),
                                        '( ){2,}', ' ', 'g')
                                    ), 
                                ' ', '&' )
                            ) 
                        ); 
    
                    ELSE
    
                        RETURN to_tsquery( 'portuguese' , 
                            sem_acento( 
                                replace( 
                                    trim(both ' ' from 
                                        REGEXP_REPLACE( 
                                            REGEXP_REPLACE( 
                                                busca
                                            , '[^a-zà-úA-ZÀ-Ú0-9\-+]', ' ', 'g'),
                                        '( ){2,}', ' ', 'g')
                                     ), 
                                ' ', '|' )
                            ) 
                        ); 

                    END IF;
                    
                END;

            $$;
            "
        );
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