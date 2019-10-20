<?php

use Illuminate\Database\Seeder;

class MenusSprint015TableSeeder extends Seeder
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
                'menu_id' => 17,
                'nome' => 'MEUS TICKETS',
                'descricao' => 'ACOMPANHAR MEUS TICKETS',
                'ativo' => 'TRUE',
                'icone' => 'search',
                'url' => '/ticket/proprio',
                'nivel' => '2',
                'ordem' => 115,
            ]
        ]);

	DB::table('menus')->where('nome', 'ACOMPANHAR TICKET')->update([
		'nome' => 'TRATAR TICKETS',
		'icone' => 'reply-all',
	]);
    }
}
