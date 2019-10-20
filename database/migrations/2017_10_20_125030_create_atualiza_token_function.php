<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAtualizaTokenFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION atualiza_token( id_usuario INT, usuario TEXT, password TEXT) RETURNS void
                AS $$
                UPDATE usuarios SET api_token = (
                    SELECT MD5(TO_CHAR(NOW(), 'YYYY-MM-DD_HH:MI:SS')||':'||'USUARIO'||':'||usuario)||
                            MD5(TO_CHAR(NOW(), 'YYYY-MM-DD_HH:MI:SS')||':'||'SENHA'||':'||password) 
                    ) 
                WHERE id = id_usuario;
                $$
            LANGUAGE 'sql';
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP FUNCTION atualiza_token( integer, text, text );");
    }
}
