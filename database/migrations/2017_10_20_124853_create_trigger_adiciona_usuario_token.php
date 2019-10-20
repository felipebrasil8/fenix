<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerAdicionaUsuarioToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION adicionar_usuario_token() RETURNS trigger
                LANGUAGE plpgsql
                AS $$
                BEGIN
                    PERFORM atualiza_token(NEW.id, NEW.usuario, NEW.password);
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
            DROP TRIGGER IF EXISTS adicionar_usuario_token ON usuarios CASCADE;
        ");
        DB::statement("DROP FUNCTION adicionar_usuario_token();");
    }
}
