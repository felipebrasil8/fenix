<?php

use Illuminate\Database\Seeder;

class PermissaoBaseDeConhecimentoSprint022TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Busca ID do menu BASE DE CONHECIMENTO
        $menu_id = DB::table('menus')->where('nome', 'MENSAGENS')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'VISUALIZAR MENSAGENS',
                'identificador' => 'BASE_MENSAGENS_VISUALIZAR'
            ]        
        ]);

        DB::table('acessos')->insert([
            [
                'nome' => 'BASE DE CONHECIMENTO - VISUALIZAR MENSAGENS'
            ]
        ]);                                         

        $permissao = DB::table('permissoes')->where('identificador', 'BASE_MENSAGENS_VISUALIZAR')->get()->first()->id;
       
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - VISUALIZAR MENSAGENS')->get()->first()->id;

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao
            ]
        ]);             
    }
}
