<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerEditaUsuarioTokenAddTableUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TRIGGER edita_usuario_token AFTER UPDATE ON usuarios FOR EACH ROW EXECUTE PROCEDURE edita_usuario_token();
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
    }
}

