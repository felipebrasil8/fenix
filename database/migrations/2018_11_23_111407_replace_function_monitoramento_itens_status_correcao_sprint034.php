<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceFunctionMonitoramentoItensStatusCorrecaoSprint034 extends Migration
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
            servidor_status RECORD;
            chamado RECORD;
            
            BEGIN
                
                SELECT INTO status_new identificador, alarme FROM monitoramento_servidores_status WHERE id = NEW.monitoramento_servidores_status_id;
                
                IF (TG_OP = 'INSERT') THEN
                    -- REGRA PARA HISTORICO DE ITENS QUANDO É PRIMEIRO
                    IF status_new.identificador <> 'FORA' THEN
                        INSERT INTO monitoramento_servidores_itens_historicos (servidores_id, servidores_itens_id, servidores_status_id, mensagem) 
                                VALUES (NEW.monitoramento_servidores_id, NEW.id, NEW.monitoramento_servidores_status_id, NEW.mensagem);
                    END IF;

                    RETURN NEW;
                ELSE

                    IF (  OLD.monitoramento_servidores_status_id <> NEW.monitoramento_servidores_status_id ) THEN

                        SELECT INTO status_old identificador, alarme FROM monitoramento_servidores_status WHERE id = OLD.monitoramento_servidores_status_id;
                        
                        SELECT INTO itens_historicos id, servidores_status_id FROM monitoramento_servidores_itens_historicos WHERE servidores_id = NEW.monitoramento_servidores_id AND servidores_itens_id = NEW.id AND data_saida IS NULL;
                        
                        UPDATE monitoramento_servidores_itens SET dt_status = NOW(), contador_falhas = 1 WHERE id = NEW.id;
                        
                        IF ( status_new.identificador = 'OK' OR status_new.identificador = 'FORA' OR status_old.identificador = 'FORA' ) THEN

                            UPDATE monitoramento_servidores_itens SET chamado_vinculado = NULL, chamado_vinculado_at = NULL, usuario_inclusao_chamado_id = NULL WHERE id = NEW.id;

                            UPDATE monitoramento_servidores_chamados SET updated_at = NOW() WHERE monitoramento_servidores_itens_id = NEW.id AND numero_chamado = OLD.chamado_vinculado AND updated_at IS NULL;

                        END IF;

                        IF ( status_old.identificador = 'OK' ) THEN
                            
                            SELECT INTO servidor_status identificador, alarme FROM monitoramento_servidores LEFT JOIN monitoramento_servidores_status ON monitoramento_servidores_status.id = monitoramento_servidores.monitoramento_servidores_status_id WHERE monitoramento_servidores.id = NEW.monitoramento_servidores_id;
    
                            IF ( servidor_status.alarme ) THEN
                                
                                SELECT INTO chamado chamado_vinculado FROM monitoramento_servidores_itens WHERE monitoramento_servidores_itens.monitoramento_servidores_id = NEW.monitoramento_servidores_id AND chamado_vinculado IS NOT NULL LIMIT 1;

                                IF FOUND THEN

                                    UPDATE monitoramento_servidores_itens SET chamado_vinculado = chamado.chamado_vinculado, chamado_vinculado_at = NOW(), usuario_inclusao_chamado_id = NULL WHERE id = NEW.id;
                                    INSERT INTO monitoramento_servidores_chamados ( numero_chamado, monitoramento_servidores_id, monitoramento_servidores_itens_id, created_at ) VALUES ( chamado.chamado_vinculado, NEW.monitoramento_servidores_id, NEW.id, NOW() );

                                END IF;
                            
                            END IF;

                        END IF;
                        
                        -- REGRA PARA HISTORICO DE ITENS QUANDO O STATUS É ALTERADO
                        IF itens_historicos.id IS NOT NULL AND itens_historicos.servidores_status_id <> NEW.monitoramento_servidores_status_id AND status_new.identificador <> 'FORA' THEN
                           
                            UPDATE monitoramento_servidores_itens_historicos SET data_saida = NOW() WHERE id = itens_historicos.id;

                            INSERT INTO monitoramento_servidores_itens_historicos (servidores_id, servidores_itens_id, servidores_status_id, mensagem) VALUES (NEW.monitoramento_servidores_id, NEW.id, NEW.monitoramento_servidores_status_id, NEW.mensagem);

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
            status_new RECORD;
            status_old RECORD;
            itens_historicos RECORD;
            servidor_status RECORD;
            chamado RECORD;
            
            BEGIN
                
                SELECT INTO status_new identificador, alarme FROM monitoramento_servidores_status WHERE id = NEW.monitoramento_servidores_status_id;
                
                IF (TG_OP = 'INSERT') THEN
                    -- REGRA PARA HISTORICO DE ITENS QUANDO É PRIMEIRO
                    IF status_new.identificador <> 'FORA' THEN
                        INSERT INTO monitoramento_servidores_itens_historicos (servidores_id, servidores_itens_id, servidores_status_id, mensagem) 
                                VALUES (NEW.monitoramento_servidores_id, NEW.id, NEW.monitoramento_servidores_status_id, NEW.mensagem);
                    END IF;

                    RETURN NEW;
                ELSE

                    IF (  OLD.monitoramento_servidores_status_id <> NEW.monitoramento_servidores_status_id ) THEN

                        SELECT INTO status_old identificador, alarme FROM monitoramento_servidores_status WHERE id = OLD.monitoramento_servidores_status_id;
                        
                        SELECT INTO itens_historicos id, servidores_status_id FROM monitoramento_servidores_itens_historicos WHERE servidores_id = NEW.monitoramento_servidores_id AND servidores_itens_id = NEW.id AND data_saida IS NULL;
                        
                        UPDATE monitoramento_servidores_itens SET dt_status = NOW(), contador_falhas = 1 WHERE id = NEW.id;
                        
                        IF ( status_new.identificador = 'OK' OR status_new.identificador = 'FORA' OR status_old.identificador = 'FORA' ) THEN

                            UPDATE monitoramento_servidores_itens SET chamado_vinculado = NULL, chamado_vinculado_at = NULL, usuario_inclusao_chamado_id = NULL WHERE id = NEW.id;

                            UPDATE monitoramento_servidores_chamados SET updated_at = NOW() WHERE monitoramento_servidores_itens_id = NEW.id AND numero_chamado = OLD.chamado_vinculado AND updated_at IS NULL;

                        END IF;

                        IF ( status_old.identificador = 'OK' ) THEN
                            
                            SELECT INTO servidor_status identificador, alarme FROM monitoramento_servidores LEFT JOIN monitoramento_servidores_status ON monitoramento_servidores_status.id = monitoramento_servidores.monitoramento_servidores_status_id WHERE monitoramento_servidores.id = NEW.monitoramento_servidores_id;
    
                            IF ( servidor_status.alarme ) THEN
                                
                                SELECT INTO chamado chamado_vinculado FROM monitoramento_servidores_itens WHERE monitoramento_servidores_itens.monitoramento_servidores_id = NEW.monitoramento_servidores_id AND chamado_vinculado IS NOT NULL LIMIT 1;

                                UPDATE monitoramento_servidores_itens SET chamado_vinculado = chamado.chamado_vinculado, chamado_vinculado_at = NOW(), usuario_inclusao_chamado_id = NULL WHERE id = NEW.id;
                                INSERT INTO monitoramento_servidores_chamados ( numero_chamado, monitoramento_servidores_id, monitoramento_servidores_itens_id, created_at ) VALUES ( chamado.chamado_vinculado, NEW.monitoramento_servidores_id, NEW.id, NOW() );
                            
                            END IF;

                        END IF;
                        
                        -- REGRA PARA HISTORICO DE ITENS QUANDO O STATUS É ALTERADO
                        IF itens_historicos.id IS NOT NULL AND itens_historicos.servidores_status_id <> NEW.monitoramento_servidores_status_id AND status_new.identificador <> 'FORA' THEN
                           
                            UPDATE monitoramento_servidores_itens_historicos SET data_saida = NOW() WHERE id = itens_historicos.id;

                            INSERT INTO monitoramento_servidores_itens_historicos (servidores_id, servidores_itens_id, servidores_status_id, mensagem) VALUES (NEW.monitoramento_servidores_id, NEW.id, NEW.monitoramento_servidores_status_id, NEW.mensagem);

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
    }
}
