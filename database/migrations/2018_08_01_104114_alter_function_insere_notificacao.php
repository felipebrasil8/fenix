<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFunctionInsereNotificacao extends Migration
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
        CREATE OR REPLACE FUNCTION insere_notificacao() RETURNS trigger LANGUAGE plpgsql AS $$

            DECLARE
                rs RECORD;
            BEGIN

                UPDATE notificacoes SET imagem = '/configuracao/usuario/avatar-grande/'||NEW.usuario_inclusao_id WHERE id = NEW.id;
                
                RETURN NEW;

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
        CREATE OR REPLACE FUNCTION insere_notificacao() RETURNS trigger LANGUAGE plpgsql AS $$

        DECLARE
            rs RECORD;
        BEGIN

            SELECT INTO rs avatar FROM funcionarios f INNER JOIN usuarios u ON u.funcionario_id = f.id WHERE u.ativo AND f.ativo AND u.id = NEW.usuario_inclusao_id;
            IF FOUND AND rs.avatar <> '' AND rs.avatar IS NOT NULL THEN
                UPDATE notificacoes SET imagem = '/img/users/'||rs.avatar||'.png' WHERE id = NEW.id;
            END IF;
            
            RETURN NEW;

        END;

        $$;
        "
        );
    }
}
