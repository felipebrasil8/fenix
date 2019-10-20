<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsereNotificacaoTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("
            CREATE OR REPLACE FUNCTION insere_notificacao() RETURNS trigger LANGUAGE plpgsql AS 
$$

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
        ");

        DB::statement("CREATE TRIGGER insere_notificacao AFTER INSERT ON notificacoes FOR EACH ROW EXECUTE PROCEDURE insere_notificacao();");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::statement("DROP TRIGGER IF EXISTS insere_notificacao ON notificacoes CASCADE;");
        DB::statement("DROP FUNCTION insere_notificacao();");

    }
}
