<?php

use Illuminate\Database\Seeder;

class PermissaoTicketSprint015TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {                                                       
        DB::table('permissoes')->where('identificador', '=', 'TICKETS_EDITAR')
            ->update([
                'identificador' => 'TICKETS_TODOS_EDITAR', 
                'descricao' => 'EDITAR TODOS OS TICKETS'
            ]);

        DB::table('permissoes')->where('identificador', '=', 'TICKETS_VISUALIZAR_PROPRIO')
            ->update([
                'menu_id' => 20
            ]);

        DB::table('permissoes')->where('identificador', '=', 'TICKETS_RESPONDER')
            ->update([ 
                'identificador' => 'TICKETS_TODOS_RESPONDER', 
                'descricao' => 'RESPONDER TODOS OS TICKETS'
            ]);

        DB::table('acesso_permissao')->where('acesso_id', '=', 10)
            ->where('permissao_id', '=', 56)
            ->update([ 
                'acesso_id' => 14
            ]);

        DB::table('acesso_permissao')->where('acesso_id', '=', 13)
            ->where('permissao_id', '=', 59)
            ->update([ 
                'acesso_id' => 14
            ]);

        DB::table('permissoes')->insert([
            [
                'menu_id' => 19,
                'descricao' => 'EDITAR TICKETS DO DEPARTAMENTO',
                'identificador' => 'TICKETS_DEPARTAMENTO_EDITAR'
            ],
            [
                'menu_id' => 19,
                'descricao' => 'RESPONDER TICKETS DO DEPARTAMENTO',
                'identificador' => 'TICKETS_DEPARTAMENTO_RESPONDER'
            ],
        ]);

       DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 13,
                'permissao_id' => 62
            ],
            [
                'acesso_id' => 10,
                'permissao_id' => 63
            ]
        ]);                 
    }
}
