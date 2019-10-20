<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerAdicionaUsuarioTokenAddTableUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TRIGGER adicionar_usuario_token AFTER INSERT ON usuarios FOR EACH ROW EXECUTE PROCEDURE adicionar_usuario_token();
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
    }
}
