<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerHistoricoUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TRIGGER insere_funcionario_historico AFTER UPDATE ON funcionarios FOR EACH ROW EXECUTE PROCEDURE insere_historico_usuario();
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
            DROP TRIGGER IF EXISTS insere_funcionario_historico ON funcionarios CASCADE;
        ");
    }
}
