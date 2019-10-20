<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePermissao extends Migration
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
            DELETE FROM acesso_permissao WHERE permissao_id IN (SELECT id FROM permissoes WHERE identificador = 'BASE_PUBLICACOES_ATIVAR' OR  identificador = 'BASE_PUBLICACOES_CATEGORIA_EXCLUIR' OR identificador = 'BASE_PUBLICACOES_CATEGORIA_EDITAR' OR identificador = 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR');
            "
        );

        DB::statement(
            "
            DELETE FROM permissoes WHERE identificador = 'BASE_PUBLICACOES_ATIVAR' OR identificador = 'BASE_PUBLICACOES_CATEGORIA_EXCLUIR' OR identificador = 'BASE_PUBLICACOES_CATEGORIA_EDITAR' OR identificador = 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR';
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
        //
    }
}
