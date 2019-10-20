<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FunctionMonitoramentoServidoresItensStatusSprint031 extends Migration
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
            CREATE OR REPLACE FUNCTION atualiza_dt_status_itens(  ) RETURNS trigger
            LANGUAGE plpgsql
            AS $$    
            
            DECLARE

            al RECORD;
            status_new RECORD;
            status_old RECORD;
            
            BEGIN
                    
                IF (  OLD.monitoramento_servidores_status_id <> NEW.monitoramento_servidores_status_id ) THEN   
                    
                    UPDATE monitoramento_servidores_itens SET dt_status = NOW(), contador_falhas = 1 WHERE id = NEW.id;

                    SELECT INTO status_new identificador, alarme FROM monitoramento_servidores_status WHERE id = NEW.monitoramento_servidores_status_id;
                    
                    SELECT INTO status_old identificador, alarme FROM monitoramento_servidores_status WHERE id = OLD.monitoramento_servidores_status_id;

                    IF ( status_new.identificador = 'OK' OR ( status_old.identificador = 'FORA' AND status_new.alarme ) ) THEN

                        UPDATE monitoramento_servidores_itens SET chamado_vinculado = NULL, usuario_inclusao_chamado_id = NULL WHERE id = NEW.id;

                    END IF;
                
                ELSE    
                    
                    SELECT INTO al alarme FROM monitoramento_servidores_status where id = NEW.monitoramento_servidores_status_id;
                    
                    IF( al.alarme = TRUE ) THEN 

                        UPDATE monitoramento_servidores_itens SET contador_falhas = (contador_falhas+1)  WHERE id = NEW.id;    
                
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
            CREATE OR REPLACE FUNCTION atualiza_dt_status_itens(  ) RETURNS trigger
            LANGUAGE plpgsql
            AS $$    
            
            DECLARE

            al RECORD;
            
            BEGIN
                    
                  
                IF (  OLD.monitoramento_servidores_status_id <> NEW.monitoramento_servidores_status_id ) THEN   
                    
                    UPDATE monitoramento_servidores_itens SET dt_status = NOW(), contador_falhas = 0 WHERE id = NEW.id;    
                
                ELSE    
                    
                    SELECT INTO al alarme FROM monitoramento_servidores_status where id = NEW.monitoramento_servidores_status_id;
                    
                    IF( al.alarme = TRUE ) THEN 

                        UPDATE monitoramento_servidores_itens SET contador_falhas = (contador_falhas+1)  WHERE id = NEW.id;    
                
                    END IF;
                END IF;
           
                RETURN NULL;
            END;

            $$;
            "
        );
    }
}
