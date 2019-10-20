<?php

use Illuminate\Database\Seeder;

class PublicacoesConteudoTipoSprint018TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('publicacoes_conteudos_tipos')->insert([
            [
            'nome' => 'TEXTO',
            ],
            [
            'nome' => 'LINHA',
            ],
            [
            'nome' => 'IMAGEM',
            ],
            [
            'nome' => 'IMAGEM COM LINK',
            ],
        ]);                                              
    }
}