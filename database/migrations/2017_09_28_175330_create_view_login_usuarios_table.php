<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewLoginUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW login_usuario AS
            SELECT
                u.id,
                u.usuario,
                u.password, 
                u.nome, 
                u.funcionario_id, 
                u.senha_alterada, 
                u.visualizado_senha_alterada, 
                u.api_token, 
                p.id AS perfil_id, 
                p.nome AS perfil_nome, 
                CASE WHEN u.funcionario_id IS NOT NULL THEN    
                    f.avatar 
                ELSE
                    'fantasma'
                END avatar 
            FROM usuarios u 
            INNER JOIN perfis p ON p.id = u.perfil_id 
            LEFT JOIN funcionarios f ON f.id = u.funcionario_id
            WHERE u.ativo 
            ORDER BY id;
        ");
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW login_usuario");
    }
}
