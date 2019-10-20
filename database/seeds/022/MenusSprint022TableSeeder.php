<?php

use Illuminate\Database\Seeder;

class MenusSprint022TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Busca ID do menu BASE DE CONHECIMENTO
        $menu_id = DB::table('menus')->where('nome', 'BASE DE CONHECIMENTO')->first()->id;

        DB::table('menus')->insert([
            [
                'menu_id' => $menu_id,
                'nome' => 'MENSAGENS',
                'descricao' => 'MENSAGENS DAS PUBLICAÇÕES',
                'ativo' => 'TRUE',
                'icone' => 'comment',
                'url' => '/base-de-conhecimento/mensagens',
                'nivel' => '2',
                'ordem' => 120,
            ]
        ]); 
    }
}
