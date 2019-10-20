<?php

use Illuminate\Database\Seeder;

class IconeSprint016TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('icones')->insert([
            [
                'icone' => 'fa-list-ul',
                'unicode' => '&#xf0ca;',
                'nome' => 'LISTA'
            ],
            [
                'icone' => 'fa-reply',
                'unicode' => '&#xf112;',
                'nome' => 'RESPONDER'
            ],
            [
                'icone' => 'fa-user',
                'unicode' => '&#xf007;',
                'nome' => 'USUÁRIO'
            ],
            [
                'icone' => 'fa-calendar',
                'unicode' => '&#xf073;',
                'nome' => 'CALENDÁRIO'
            ],
            [
                'icone' => 'fa-bell',
                'unicode' => '&#xf0f3;',
                'nome' => 'SINO'
            ],
            [
                'icone' => 'fa-check-square-o',
                'unicode' => '&#xf046;',
                'nome' => 'CHECKBOX MARCADO'
            ],
            [
                'icone' => 'fa-star-half-o',
                'unicode' => '&#xf123;',
                'nome' => 'ESTRELA'
            ],
            [
                'icone' => 'fa-flag-checkered',
                'unicode' => '&#xf11e;',
                'nome' => 'BANDEIRA XADREZ'
            ],
            [
                'icone' => 'fa-undo',
                'unicode' => '&#xf0e2;',
                'nome' => 'DESFAZER'
            ], 
            [
                'icone' => 'fa-times',
                'unicode' => '&#xf00d;',
                'nome' => 'FECHAR'
            ],     
            [
                'icone' => 'fa-pencil',
                'unicode' => '&#xf040;',
                'nome' => 'EDITAR'
            ],         
        ]);
    }
}
