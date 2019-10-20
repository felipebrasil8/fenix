<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionMaxDate extends Migration
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
            CREATE OR REPLACE FUNCTION max_date( date, date ) RETURNS date
                LANGUAGE plpgsql
                AS $$    
                            
                BEGIN
                    
                    IF ($1 >= $2) THEN
                        RETURN $1;
                    ELSE
                        RETURN $2;
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
        DB::statement("DROP FUNCTION max_date( date, date );");
    }
}
