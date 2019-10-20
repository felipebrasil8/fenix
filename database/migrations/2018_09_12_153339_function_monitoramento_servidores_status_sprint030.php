<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FunctionMonitoramentoServidoresStatusSprint030 extends Migration
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
            CREATE OR REPLACE FUNCTION atualiza_dt_status_servidores(  ) RETURNS trigger
                LANGUAGE plpgsql
                AS $$    
                
                DECLARE

                al RECORD;
                BEGIN
                    
                    IF ( OLD.monitoramento_servidores_status_id = NEW.monitoramento_servidores_status_id ) THEN
                   
                        SELECT INTO al alarme FROM monitoramento_servidores_status where id = NEW.monitoramento_servidores_status_id;
                        
                        IF( al.alarme = TRUE ) THEN 

                            UPDATE monitoramento_servidores SET contador_falhas = (contador_falhas+1)  WHERE id = NEW.id;    
                    
                        END IF;
    
                    ELSE    
    
                        UPDATE monitoramento_servidores SET dt_status = NOW(), contador_falhas = 0 WHERE id = NEW.id;
                    
                    END IF;

                    RETURN NULL;  
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
        DB::statement(

            "DROP FUNCTION IF EXISTS atualiza_dt_status_servidores();"
        );
    }
}
