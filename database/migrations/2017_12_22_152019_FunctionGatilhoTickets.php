<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FunctionGatilhoTickets extends Migration
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
CREATE OR REPLACE FUNCTION gatilho_tickets() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE
   
    x text; 
    v text; 
    
    msg text;
    tit text;
    urlProrio text;
    urlDep text;

    rs RECORD;
    rsx RECORD;
    rsa RECORD;
    rsan RECORD;
    rsaf RECORD;
    rsanu RECORD;

    notificacao_usuario_inclusao_id int;

    BEGIN
                
    FOR rs IN SELECT quanto_executar as x, acao as a FROM tickets_gatilho WHERE ativo = true AND departamento_id = NEW.departamento_id ORDER BY ordem
    LOOP
            
        SELECT INTO rsx * FROM json_each_text(rs.x);   
        SELECT INTO rsa * FROM json_each_text(rs.a); 
        v := rsa.value;
     
        IF rsx.key = 'status' AND rsx.value::int = COALESCE(NEW.tickets_status_id, 0)::int AND ( TG_OP = 'INSERT' OR (TG_OP = 'UPDATE' AND COALESCE(OLD.tickets_status_id, 0)::int != NEW.tickets_status_id )) THEN
            
            -- Resolução
            IF rsa.key = 'dt_resolucao' THEN
                IF v = 'null' THEN
                    UPDATE tickets SET dt_resolucao = null WHERE id = NEW.id;
                ELSE
                    UPDATE tickets SET dt_resolucao = v::timestamp WHERE id = NEW.id;
                END IF;
            END IF;
            -- End Resolução 

            -- Fechamento
            IF rsa.key = 'dt_fechamento' THEN
                IF v = 'null' THEN
                    UPDATE tickets SET dt_fechamento = null WHERE id = NEW.id;
                ELSE
                    UPDATE tickets SET dt_fechamento = v::timestamp WHERE id = NEW.id;
                END IF;
            END IF;  
            -- End Fechamento

            -- Previsao
            IF rsa.key = 'dt_previsao' THEN
                IF v = 'null' THEN
                    UPDATE tickets SET dt_previsao = null WHERE id = NEW.id;
                ELSE
                    UPDATE tickets SET dt_previsao = v::timestamp WHERE id = NEW.id;
                END IF;
            END IF;  
            -- End Previsao

            -- Notificacao
            IF rsa.key = 'dt_notificacao' THEN
                IF v = 'null' THEN
                    UPDATE tickets SET dt_notificacao = null WHERE id = NEW.id;
                ELSE
                    UPDATE tickets SET dt_notificacao = v::timestamp WHERE id = NEW.id;
                END IF;
            END IF;
            -- End Notificacao
            
            -- Responsavel
            IF rsa.key = 'responsavel' THEN
                UPDATE tickets SET usuario_responsavel_id = v::int WHERE id = NEW.id;
            END IF;
            
        END IF;

        
        IF rsx.key = 'dt_notificacao' AND rsx.value = 'mudou' AND  TG_OP = 'UPDATE' THEN
            SELECT INTO msg value FROM json_each_text(rsa.value::json) WHERE key = 'mensagem';
                msg := '#'||NEW.codigo||' - '||msg;

            SELECT INTO tit replace(concat(Upper(substr(nome, 1,1)), lower(substr(nome, 2,length(nome)))),'de ti', 'de TI') FROM departamentos WHERE id = NEW.departamento_id;
                    
                  
            urlProrio := '/ticket/proprio/'||NEW.id;
            urlDep :=  '/ticket/'||NEW.id;

        
            IF  NEW.notificacao_id IS NOT NULL AND NEW.dt_notificacao IS NULL THEN
            
                UPDATE tickets SET notificacao_id = NULL WHERE id = NEW.id;
                DELETE FROM notificacoes WHERE id = NEW.notificacao_id;
            
            
            END IF;
            
            IF   NEW.notificacao_id IS NOT NULL AND OLD.dt_notificacao IS NOT NULL AND NEW.dt_notificacao IS NOT NULL AND OLD.dt_notificacao != NEW.dt_notificacao THEN 
                
                UPDATE tickets SET notificacao_id = NULL WHERE id = NEW.id;
                DELETE FROM notificacoes WHERE id = NEW.notificacao_id;
                    
                INSERT INTO notificacoes ( usuario_inclusao_id, usuario_id, titulo, mensagem, modulo, url, icone ) 
                    VALUES  ( NEW.usuario_alteracao_id, NEW.usuario_responsavel_id , tit, msg, 'ticket', urlDep, 'ticket' );

                UPDATE tickets SET notificacao_id = CURRVAL('notificacoes_id_seq') WHERE id = NEW.id;
                
            END IF;

            IF NEW.notificacao_id IS NULL AND OLD.dt_notificacao IS NULL AND NEW.dt_notificacao IS NOT NULL THEN
            
                INSERT INTO notificacoes ( created_at, usuario_inclusao_id, usuario_id, titulo, mensagem, modulo, url, icone ) 
                        VALUES  ( NEW.dt_notificacao, NEW.usuario_alteracao_id, NEW.usuario_responsavel_id , tit, msg, 'ticket', urlDep, 'ticket' );

                UPDATE tickets SET notificacao_id = CURRVAL('notificacoes_id_seq') WHERE id = NEW.id;
                
            END IF;

        END IF;


        -- Notificação      
        IF rsa.key = 'notificacao' AND ( 
             ( rsx.key = 'status' AND rsx.value = COALESCE(NEW.tickets_status_id, 0)::text AND ( TG_OP = 'INSERT' OR ( TG_OP = 'UPDATE' AND COALESCE(OLD.tickets_status_id, 0)::int != NEW.tickets_status_id )))
                OR (  rsx.key = 'responsavel' AND rsx.value = 'mudou' AND TG_OP = 'UPDATE' AND COALESCE(OLD.usuario_responsavel_id, 0)::int != NEW.usuario_responsavel_id ) 
                    OR  (  rsx.key = 'responsavel' AND rsx.value = COALESCE(NEW.usuario_responsavel_id, 0)::text AND ( TG_OP = 'INSERT' OR ( TG_OP = 'UPDATE' AND COALESCE(OLD.usuario_responsavel_id, 0)::int != NEW.usuario_responsavel_id )))
                    ) THEN
           
            FOR rsan IN SELECT * FROM json_each_text(rsa.value::json)
            LOOP
                
                SELECT INTO msg value FROM json_each_text(rsa.value::json) WHERE key = 'mensagem';
                msg := '#'||NEW.codigo||' - '||msg;

                SELECT INTO tit replace(concat(Upper(substr(nome, 1,1)), lower(substr(nome, 2,length(nome)))),'de ti', 'de TI') FROM departamentos WHERE id = NEW.departamento_id;
                    
                    
                urlProrio := '/ticket/proprio/'||NEW.id;
                urlDep :=  '/ticket/'||NEW.id;

                IF TG_OP = 'INSERT' THEN
                    notificacao_usuario_inclusao_id := NEW.usuario_inclusao_id;
                ELSE
                    notificacao_usuario_inclusao_id := NEW.usuario_alteracao_id;
                END IF;

                IF rsan.key = 'solicitante' AND rsan.value != 'false' AND NEW.usuario_alteracao_id != NEW.usuario_solicitante_id THEN
                    INSERT INTO notificacoes ( usuario_inclusao_id, usuario_id, titulo, mensagem, modulo, url, icone ) 
                        VALUES  ( notificacao_usuario_inclusao_id, NEW.usuario_solicitante_id , tit, msg, 'meus_tickets', urlProrio, 'ticket' );
                END IF;
                
                IF rsan.key = 'responsavel' AND rsan.value != 'false' AND COALESCE(NEW.usuario_responsavel_id, 0) != 0 AND NEW.usuario_alteracao_id != NEW.usuario_responsavel_id THEN
                    INSERT INTO notificacoes ( usuario_inclusao_id, usuario_id, titulo, mensagem, modulo, url, icone ) 
                        VALUES  ( notificacao_usuario_inclusao_id, NEW.usuario_responsavel_id , tit, msg, 'ticket', urlDep, 'ticket' );
                END IF;

                IF rsan.key = 'departamento' AND rsan.value != 'false' THEN
                                                               
                    INSERT INTO notificacoes ( usuario_inclusao_id, usuario_id, titulo, mensagem, modulo, url, icone ) 
                         SELECT notificacao_usuario_inclusao_id, u.id::int, tit, msg, 'ticket', urlDep, 'ticket' FROM usuarios u
                         LEFT JOIN funcionarios f ON u.funcionario_id = f.id     
                         LEFT JOIN cargos c ON f.cargo_id = c.id     
                         WHERE c.departamento_id IN (SELECT value::int FROM json_each_text(rsan.value::json) );
                                                          
                END IF;

                IF rsan.key = 'cargo' AND rsan.value != 'false' THEN

                    INSERT INTO notificacoes ( usuario_inclusao_id, usuario_id, titulo, mensagem, modulo, url, icone ) 
                         SELECT notificacao_usuario_inclusao_id, id::int, tit, msg, 'ticket', urlDep, 'ticket' FROM usuarios u
                             LEFT JOIN funcionarios f ON u.funcionario_id = f.id               
                            WHERE cargo_id IN (SELECT value FROM json_each_text(rsan.value::json) );

                END IF;

                IF rsan.key = 'usuario' AND rsan.value != 'false' THEN
                  
                     INSERT INTO notificacoes ( usuario_inclusao_id, usuario_id, titulo, mensagem, modulo, url, icone ) 
                        SELECT notificacao_usuario_inclusao_id, value::int, tit, msg, 'ticket', urlDep, 'ticket' FROM json_each_text(rsan.value::json) WHERE NEW.usuario_alteracao_id != value::int;
                
                END IF;
            

            END LOOP;

        END IF;  
        -- End notificação
        
       
    END LOOP;


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

       
        DB::statement("
            DROP TRIGGER IF EXISTS triger_gatilho_tickets ON tickets CASCADE;
        ");
        DB::statement("DROP FUNCTION gatilho_tickets();");
       
    }
}
