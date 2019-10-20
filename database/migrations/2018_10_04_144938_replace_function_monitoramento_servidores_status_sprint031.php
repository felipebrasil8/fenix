<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceFunctionMonitoramentoServidoresStatusSprint031 extends Migration
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
                itens_historicos RECORD;

                BEGIN

                    IF ( OLD.monitoramento_servidores_status_id = NEW.monitoramento_servidores_status_id ) THEN
                   
                        SELECT INTO al alarme FROM monitoramento_servidores_status where id = NEW.monitoramento_servidores_status_id;
                        
                        IF( al.alarme = TRUE ) THEN 

                            UPDATE monitoramento_servidores SET contador_falhas = (contador_falhas+1) WHERE id = NEW.id;    
                    
                        END IF;
    
                    ELSE 
    
                        UPDATE monitoramento_servidores SET dt_status = NOW(), contador_falhas = 1 WHERE id = NEW.id;
                        
                        SELECT INTO itens_historicos id, servidores_status_id FROM monitoramento_servidores_itens_historicos WHERE servidores_id = NEW.id AND servidores_itens_id IS NULL AND servidores_status_id IS NOT NULL AND data_saida IS NULL;
                        
                        -- REGRA PARA HISTORICO DE SERVIDOR QUANDO O STATUS É ALTERADO
                        IF itens_historicos.id IS NULL THEN
                            
                            INSERT INTO monitoramento_servidores_itens_historicos (servidores_id, servidores_itens_id, servidores_status_id) VALUES (NEW.id, NULL, NEW.monitoramento_servidores_status_id);

                        ELSIF itens_historicos.id IS NOT NULL AND itens_historicos.servidores_status_id <> NEW.monitoramento_servidores_status_id THEN

                            UPDATE monitoramento_servidores_itens_historicos SET data_saida = NOW() WHERE id = itens_historicos.id;
                            INSERT INTO monitoramento_servidores_itens_historicos (servidores_id, servidores_itens_id, servidores_status_id) VALUES (NEW.id, NULL, NEW.monitoramento_servidores_status_id);

                        END IF;
                    
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
}
