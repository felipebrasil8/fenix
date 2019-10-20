<?php

use Illuminate\Database\Seeder;

class MenusSprint013TableSeeder extends Seeder
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
                'menu_id' => 6,
                'nome' => 'ÁREAS',
                'descricao' => 'GESTÃO DE ÁREAS DA EMPRESA',
                'ativo' => 'TRUE',
                'icone' => 'group',
                'url' => '/rh/area',
                'nivel' => '2',
                'ordem' => 105,
            ],
            [
                'menu_id' => 6,
                'nome' => 'DEPARTAMENTOS',
                'descricao' => 'GESTÃO DE DEPARTAMENTOS DA EMPRESA',
                'ativo' => 'TRUE',
                'icone' => 'group',
                'url' => '/rh/departamento',
                'nivel' => '2',
                'ordem' => 106,
            ],
            [
                'menu_id' => 6,
                'nome' => 'CARGOS',
                'descricao' => 'GESTÃO DE CARGOS DA EMPRESA',
                'ativo' => 'TRUE',
                'icone' => 'group',
                'url' => '/rh/cargo',
                'nivel' => '2',
                'ordem' => 107,
            ],
            [
                'menu_id' => 2,
                'nome' => 'TICKETS',
                'descricao' => 'PÁGINA DE CONFIGURAÇÃO DE TICKETS',
                'ativo' => 'TRUE',
                'icone' => 'ticket',
                'url' => '/configuracao/ticket',
                'nivel' => '2',
                'ordem' => 135,
            ],
            [
                'menu_id' => NULL,
                'nome' => 'TICKETS',
                'descricao' => 'ABERTURA E ACOMPANHAMENTO DE TICKETS',
                'ativo' => 'TRUE',
                'icone' => 'ticket',
                'url' => '',
                'nivel' => '1',
                'ordem' => 1140,
            ],
            [
                'menu_id' => 17,
                'nome' => 'ABRIR TICKET',
                'descricao' => 'ABRIR NOVO TICKET',
                'ativo' => 'TRUE',
                'icone' => 'plus-square',
                'url' => '/ticket/create',
                'nivel' => '2',
                'ordem' => 110,
            ],
            [
                'menu_id' => 17,
                'nome' => 'ACOMPANHAR TICKET',
                'descricao' => 'ACOMPANHAR TICKETS',
                'ativo' => 'TRUE',
                'icone' => 'search',
                'url' => '/ticket',
                'nivel' => '2',
                'ordem' => 120,
            ]
        ]);
    }
}
