<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW usuarios_view AS
            SELECT      
                u.id,      
                u.usuario_inclusao_id,
                u.usuario_alteracao_id,
                u.funcionario_id,                
                u.perfil_id,
                u.nome,
                u.password,
                u.usuario,          
                u.ativo,                
                p.nome AS nome_perfil,
                u.senha_alterada,
                u.visualizado_senha_alterada
            FROM usuarios u            
            LEFT JOIN perfis p on p.id = u.perfil_id            
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        DB::statement("DROP VIEW usuarios_view");
    }
}
