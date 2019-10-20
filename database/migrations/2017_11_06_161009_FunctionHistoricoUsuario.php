<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FunctionHistoricoUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::statement("
            CREATE OR REPLACE FUNCTION insere_historico_usuario() RETURNS trigger
                LANGUAGE plpgsql
                AS $$
                DECLARE
             
                alteracao TEXT;    
                antigo TEXT;
                novo TEXT;
                
                BEGIN
                    -- VERIFICA SE ALTEROU O CAMPO USUARIO OU A SENHA
                    
                    IF ( COALESCE(OLD.cargo_id, 0) != COALESCE(NEW.cargo_id, 0)  OR COALESCE(OLD.gestor_id, 0) != COALESCE(NEW.gestor_id, 0) ) THEN                        

                        alteracao := '{';
                        
                        IF ( COALESCE(OLD.cargo_id, 0) != COALESCE(NEW.cargo_id, 0) ) THEN
                            antigo := (SELECT nome FROM cargos WHERE id = COALESCE(OLD.cargo_id, 0));
                            novo := (SELECT nome FROM cargos WHERE id = COALESCE(NEW.cargo_id, 0));

                            alteracao := alteracao || '\"cargo\":{\"old\": \"'||COALESCE(antigo, '')||'\", \"new\": \"'||COALESCE(novo, '')||'\"}';
                       
                        END IF;

                        IF ( COALESCE(OLD.gestor_id, 0) != COALESCE(NEW.gestor_id,0 ) ) THEN
                            IF ( COALESCE(OLD.cargo_id, 0) != COALESCE(NEW.cargo_id, 0) ) THEN
                            
                            alteracao := alteracao || ',';
                       
                            END IF;

                            antigo := (SELECT nome FROM funcionarios WHERE id = COALESCE(OLD.gestor_id, 0));
                            novo := (SELECT nome FROM funcionarios WHERE id = COALESCE(NEW.gestor_id, 0));
                            
                            alteracao :=  alteracao || '\"gestor\":{\"old\": \"'||COALESCE(antigo, '')||'\", \"new\": \"'||COALESCE(novo, '')||'\"}';
                       
                        END IF;
                        
                        alteracao := alteracao || '}';

                       

                        
                INSERT INTO funcionarios_historico (usuario_inclusao_id, funcionario_id, alteracao) VALUES (NEW.usuario_alteracao_id, NEW.id, alteracao::JSON );
    

                    END IF;

                    RETURN NULL;
                END;
            $$;
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
            DROP TRIGGER IF EXISTS insere_historico_usuario ON funcionarios CASCADE;
        ");
        DB::statement("DROP FUNCTION insere_historico_usuario();");
    }
}
