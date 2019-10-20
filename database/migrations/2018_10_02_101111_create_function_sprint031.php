<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFunctionSprint031 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("
            CREATE OR REPLACE FUNCTION monitoramento_incluir_porta_api() RETURNS trigger LANGUAGE plpgsql AS 
            $$

            DECLARE
                rs RECORD;
                v_nome text;
            BEGIN

                IF NEW.grupo = 'NXT-URATIVA' THEN
                    v_nome = 'MONITORAMENTO_PORTA_NXTURATIVA';
                ELSIF NEW.grupo = 'NXT-DISCADOR' THEN
                    v_nome = 'MONITORAMENTO_PORTA_NXTDISCADOR';
                ELSE
                    v_nome = 'MONITORAMENTO_PORTA_NXT3000';

                    IF NEW.tipo = 'REDUNDÂNCIA' THEN
                        v_nome = 'MONITORAMENTO_PORTA_NXT3000_REDUNDANTE';
                    END IF;
                END IF;

                SELECT INTO rs valor_numero FROM parametros WHERE nome = v_nome;
                
                IF FOUND THEN
                    NEW.porta_api = rs.valor_numero;
                END IF;
                
                RETURN NEW;
            END;

            $$;
        ");

        DB::statement("CREATE TRIGGER insere_porta_api BEFORE INSERT ON monitoramento_servidores FOR EACH ROW EXECUTE PROCEDURE monitoramento_incluir_porta_api();");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS insere_porta_api ON monitoramento_servidores CASCADE;");
        DB::statement("DROP FUNCTION monitoramento_incluir_porta_api();");
    }
}
