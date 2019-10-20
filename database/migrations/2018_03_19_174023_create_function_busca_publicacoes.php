<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionBuscaPublicacoes extends Migration
{
    /**
     * Run the migrations.
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
                        RETURN to_tsquery( 'portuguese' , sem_acento( replace( busca , ' ' , '&' ) ) ); 
                    ELSE
                        RETURN to_tsquery( 'portuguese' , sem_acento( replace( busca , ' ' , '|' ) ) );
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
        DB::statement("DROP FUNCTION formata_tsquery( text, boolean );");
    }
}
