<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerEditaUsuarioToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION edita_usuario_token() RETURNS trigger
                LANGUAGE plpgsql
                AS $$
                BEGIN
                    -- VERIFICA SE ALTEROU O CAMPO USUARIO OU A SENHA
                    IF ( OLD.usuario != NEW.usuario OR OLD.password != NEW.password ) THEN
                        PERFORM atualiza_token(NEW.id, NEW.usuario, NEW.password);
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
            DROP TRIGGER IF EXISTS edita_usuario_token ON usuarios CASCADE;
        ");
        DB::statement("DROP FUNCTION edita_usuario_token();");
    }
}
