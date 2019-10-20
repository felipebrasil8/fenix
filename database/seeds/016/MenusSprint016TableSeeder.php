<?php

use Illuminate\Database\Seeder;

class MenusSprint016TableSeeder extends Seeder
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
                'nome' => 'DASHBOARD',
                'descricao' => 'VISUALIZAR DASHBOARD',
                'ativo' => 'TRUE',
                'icone' => 'dashboard',
                'url' => '/ticket/dashboard',
                'nivel' => '2',
                'ordem' => 105,
            ]
        ]);	

        DB::table('acessos')->insert([
            [
                'nome' => 'TICKETS - VISUALIZAR DASHBOARD'
            ]
        ]);
    }
}
