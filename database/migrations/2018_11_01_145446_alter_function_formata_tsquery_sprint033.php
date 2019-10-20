<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFunctionFormataTsquerySprint033 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP FUNCTION formata_tsquery(busca TEXT, busca_e boolean);");
        
        DB::statement(

            "
            CREATE OR REPLACE FUNCTION formata_tsquery( busca TEXT, busca_e boolean ) RETURNS tsquery
                LANGUAGE plpgsql
                AS $$    
                            
                BEGIN
                    
                    IF ( busca_e = TRUE) THEN
                   
                        RETURN sem_acento( 
                                replace( 
                                    trim(both ' ' from 
                                        REGEXP_REPLACE( 
                                            REGEXP_REPLACE( 
                                                busca
                                            , '[^a-zà-úA-ZÀ-Ú0-9\-+]', ' ', 'g'),
                                        '( ){2,}', ' ', 'g')
                                    ), 
                                ' ', '&' )
                            ); 
    
                    ELSE
    
                        RETURN sem_acento( 
                                replace( 
                                    trim(both ' ' from 
                                        REGEXP_REPLACE( 
                                            REGEXP_REPLACE( 
                                                busca
                                            , '[^a-zà-úA-ZÀ-Ú0-9\-+]', ' ', 'g'),
                                        '( ){2,}', ' ', 'g')
                                     ), 
                                ' ', '|' )
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
        DB::statement("DROP FUNCTION formata_tsquery(busca TEXT, busca_e boolean);");

        DB::statement(

            "
            CREATE OR REPLACE FUNCTION formata_tsquery( busca TEXT, busca_e boolean ) RETURNS text
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
}
