<?php

use Illuminate\Database\Seeder;

class MenusSprint018TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'menu_id' => NULL,
                'nome' => 'BASE DE CONHECIMENTO',
                'descricao' => 'BASE DE CONHECIMENTO',
                'ativo' => 'TRUE',
                'icone' => 'book',
                'url' => '',
                'nivel' => '1',
                'ordem' => 1135,
            ]
        ]);

        // Busca ID do menu BASE DE CONHECIMENTO
        $menu_id = DB::table('menus')->where('nome', 'BASE DE CONHECIMENTO')->first()->id;

        DB::table('menus')->insert([
            [
                'menu_id' => $menu_id,
                'nome' => 'PUBLICAÇÕES',
                'descricao' => 'PUBLICAÇÕES DA BASE DE CONHECIMENTO',
                'ativo' => 'TRUE',
                'icone' => 'share-alt',
                'url' => '/base-de-conhecimento/publicacoes',
                'nivel' => '2',
                'ordem' => 110,
            ]
        ]); 
    }
}
