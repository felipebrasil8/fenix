<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceFunctionMonitoramentoServidoresItensStatusSprint031 extends Migration
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
            itens_historicos RECORD;
            
            BEGIN
                
                SELECT INTO status_new identificador, alarme FROM monitoramento_servidores_status WHERE id = NEW.monitoramento_servidores_status_id;
                
                IF (TG_OP = 'INSERT') THEN
                    -- REGRA PARA HISTORICO DE ITENS QUANDO É PRIMEIRO
                    IF status_new.identificador <> 'FORA' THEN
                        INSERT INTO monitoramento_servidores_itens_historicos (servidores_id, servidores_itens_id, servidores_status_id) 
                                VALUES (NEW.monitoramento_servidores_id, NEW.id, NEW.monitoramento_servidores_status_id);
                    END IF;

                    RETURN NEW;
                ELSE

                    IF (  OLD.monitoramento_servidores_status_id <> NEW.monitoramento_servidores_status_id ) THEN

                        SELECT INTO status_old identificador, alarme FROM monitoramento_servidores_status WHERE id = OLD.monitoramento_servidores_status_id;
                        
                        SELECT INTO itens_historicos id, servidores_status_id FROM monitoramento_servidores_itens_historicos WHERE servidores_id = NEW.monitoramento_servidores_id AND servidores_itens_id = NEW.id AND data_saida IS NULL;
                        
                        UPDATE monitoramento_servidores_itens SET dt_status = NOW(), contador_falhas = 1 WHERE id = NEW.id;

                        IF ( status_new.identificador = 'OK' OR ( status_old.identificador = 'FORA' AND status_new.alarme ) OR status_new.identificador = 'FORA' ) THEN

                            UPDATE monitoramento_servidores_itens SET chamado_vinculado = NULL, chamado_vinculado_at = NULL, usuario_inclusao_chamado_id = NULL WHERE id = NEW.id;

                            UPDATE monitoramento_servidores_chamados SET updated_at = NOW() WHERE monitoramento_servidores_itens_id = NEW.id AND numero_chamado = OLD.chamado_vinculado AND updated_at IS NULL;

                        END IF;
                        
                        -- REGRA PARA HISTORICO DE ITENS QUANDO O STATUS É ALTERADO
                        IF itens_historicos.id IS NOT NULL AND itens_historicos.servidores_status_id <> NEW.monitoramento_servidores_status_id AND status_new.identificador <> 'FORA' THEN
                           
                            UPDATE monitoramento_servidores_itens_historicos SET data_saida = NOW() WHERE id = itens_historicos.id;

                            INSERT INTO monitoramento_servidores_itens_historicos (servidores_id, servidores_itens_id, servidores_status_id) VALUES (NEW.monitoramento_servidores_id, NEW.id, NEW.monitoramento_servidores_status_id);

                        END IF;
                    
                    ELSE    
                        
                        SELECT INTO al alarme FROM monitoramento_servidores_status where id = NEW.monitoramento_servidores_status_id;
                        
                        IF( al.alarme = TRUE ) THEN 

                            UPDATE monitoramento_servidores_itens SET contador_falhas = (contador_falhas+1)  WHERE id = NEW.id;    
                    
                        END IF;

                    END IF;

                    RETURN NEW;

                END IF;

                RETURN NULL;
           
            END;

            $$;
            "
        );

        DB::statement("
            DROP TRIGGER IF EXISTS triger_monitoramento_servidores_itens_status ON monitoramento_servidores_itens CASCADE;
        ");

        DB::statement("
            CREATE TRIGGER triger_monitoramento_servidores_itens_status AFTER INSERT OR UPDATE OF updated_at ON monitoramento_servidores_itens FOR EACH ROW EXECUTE 
            PROCEDURE atualiza_dt_status_itens();
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
            DROP TRIGGER IF EXISTS triger_monitoramento_servidores_itens_status ON monitoramento_servidores_itens CASCADE;
        ");

        DB::statement("
            CREATE TRIGGER triger_monitoramento_servidores_itens_status AFTER UPDATE OF updated_at ON monitoramento_servidores_itens FOR EACH ROW EXECUTE 
            PROCEDURE atualiza_dt_status_itens();
        ");

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
                    
                    UPDATE monitoramento_servidores_itens SET dt_status = NOW(), contador_falhas = 0 WHERE id = NEW.id;

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
}