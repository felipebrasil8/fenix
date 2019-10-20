<?php

use Illuminate\Database\Seeder;

class PublicacoesCategoriasSprint018TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('publicacoes_categorias')->insert([
            [
            'nome' => 'PÁGINA INICIAL',
            'descricao' => 'PÁGINA INICIAL',
            'publicacao_categoria_id' => NULL,
            'icone' => 'home',
            'ordem' => 10,
            ],
            [
            'nome' => 'FAQ',
            'descricao' => 'FAQ',
            'publicacao_categoria_id' => NULL,
            'icone' => 'question-circle',
            'ordem' => 20,
            ],
            [
            'nome' => 'LIÇÕES',
            'descricao' => 'LIÇÕES',
            'publicacao_categoria_id' => NULL,
            'icone' => 'map-signs',
            'ordem' => 30,
            ],
            [
            'nome' => 'BASE',
            'descricao' => 'BASE',
            'publicacao_categoria_id' => NULL,
            'icone' => 'book',
            'ordem' => 40,
            ],
            [
            'nome' => 'PROCESSOS',
            'descricao' => 'PROCESSOS',
            'publicacao_categoria_id' => NULL,
            'icone' => 'sitemap',
            'ordem' => 50,
            ],
            [
            'nome' => 'COMUNICAÇÃO INTERNA',
            'descricao' => 'COMUNICAÇÃO INTERNA',
            'publicacao_categoria_id' => NULL,
            'icone' => 'bullhorn',
            'ordem' => 60,
            ],
            [
            'nome' => 'LINKS',
            'descricao' => 'LINKS',
            'publicacao_categoria_id' => NULL,
            'icone' => 'link',
            'ordem' => 70,
            ],
        ]);

        $id_faq = DB::table('publicacoes_categorias')->where('nome', 'FAQ')->first()->id;
        DB::table('publicacoes_categorias')->insert([
            [
            'nome' => 'FAQ',
            'descricao' => 'FAQ',
            'publicacao_categoria_id' => $id_faq,
            'icone' => 'angle-double-right',
            'ordem' => 110,
            ],
            [
            'nome' => 'VOCÊ SABIA?',
            'descricao' => 'VOCÊ SABIA?',
            'publicacao_categoria_id' => $id_faq,
            'icone' => 'angle-double-right',
            'ordem' => 120,
            ],
        ]);

        $id_base = DB::table('publicacoes_categorias')->where('nome', 'BASE')->first()->id;
        DB::table('publicacoes_categorias')->insert([
            [
            'nome' => 'NXT3000',
            'descricao' => 'NXT3000',
            'publicacao_categoria_id' => $id_base,
            'icone' => 'angle-double-right',
            'ordem' => 210,
            ],
            [
            'nome' => 'NXT-DISCADOR',
            'descricao' => 'NXT-DISCADOR',
            'publicacao_categoria_id' => $id_base,
            'icone' => 'angle-double-right',
            'ordem' => 220,
            ],
            [
            'nome' => 'NXT-URATIVA',
            'descricao' => 'NXT-URATIVA',
            'publicacao_categoria_id' => $id_base,
            'icone' => 'angle-double-right',
            'ordem' => 230,
            ],
            [
            'nome' => 'NXT-IPBX',
            'descricao' => 'NXT-IPBX',
            'publicacao_categoria_id' => $id_base,
            'icone' => 'angle-double-right',
            'ordem' => 240,
            ],
            [
            'nome' => 'GERAL',
            'descricao' => 'GERAL',
            'publicacao_categoria_id' => $id_base,
            'icone' => 'angle-double-right',
            'ordem' => 250,
            ],
            [
            'nome' => 'VERSÕES',
            'descricao' => 'VERSÕES',
            'publicacao_categoria_id' => $id_base,
            'icone' => 'angle-double-right',
            'ordem' => 260,
            ],
        ]);

        $id_processos = DB::table('publicacoes_categorias')->where('nome', 'PROCESSOS')->first()->id;
        DB::table('publicacoes_categorias')->insert([
            [
            'nome' => 'PROCESSOS DE NEGÓCIOS',
            'descricao' => 'PROCESSOS DE NEGÓCIOS',
            'publicacao_categoria_id' => $id_processos,
            'icone' => 'angle-double-right',
            'ordem' => 310,
            ],
            [
            'nome' => 'PROCESSOS DE SUPORTE (APOIO)',
            'descricao' => 'PROCESSOS DE SUPORTE (APOIO)',
            'publicacao_categoria_id' => $id_processos,
            'icone' => 'angle-double-right',
            'ordem' => 320,
            ],
            [
            'nome' => 'TAREFAS/PROCEDIMENTOS',
            'descricao' => 'TAREFAS/PROCEDIMENTOS',
            'publicacao_categoria_id' => $id_processos,
            'icone' => 'angle-double-right',
            'ordem' => 330,
            ],
            [
            'nome' => 'LEGENDAS BIZAGI',
            'descricao' => 'LEGENDAS BIZAGI',
            'publicacao_categoria_id' => $id_processos,
            'icone' => 'angle-double-right',
            'ordem' => 340,
            ],
        ]);

        $id_comunicacao = DB::table('publicacoes_categorias')->where('nome', 'COMUNICAÇÃO INTERNA')->first()->id;
        DB::table('publicacoes_categorias')->insert([
            [
            'nome' => 'GESTÃO DO CONHECIMENTO',
            'descricao' => 'GESTÃO DO CONHECIMENTO',
            'publicacao_categoria_id' => $id_comunicacao,
            'icone' => 'angle-double-right',
            'ordem' => 410,
            ],
            [
            'nome' => 'GESTÃO DE PESSOAS',
            'descricao' => 'GESTÃO DE PESSOAS',
            'publicacao_categoria_id' => $id_comunicacao,
            'icone' => 'angle-double-right',
            'ordem' => 420,
            ],
            [
            'nome' => 'TREINAMENTO',
            'descricao' => 'TREINAMENTO',
            'publicacao_categoria_id' => $id_comunicacao,
            'icone' => 'angle-double-right',
            'ordem' => 430,
            ],
            [
            'nome' => 'SISTEMAS',
            'descricao' => 'SISTEMAS',
            'publicacao_categoria_id' => $id_comunicacao,
            'icone' => 'angle-double-right',
            'ordem' => 440,
            ],
            [
            'nome' => 'ADMINISTRATIVO/FINANCEIRO',
            'descricao' => 'ADMINISTRATIVO/FINANCEIRO',
            'publicacao_categoria_id' => $id_comunicacao,
            'icone' => 'angle-double-right',
            'ordem' => 450,
            ],
            [
            'nome' => 'MARKETING',
            'descricao' => 'MARKETING',
            'publicacao_categoria_id' => $id_comunicacao,
            'icone' => 'angle-double-right',
            'ordem' => 460,
            ],
            [
            'nome' => 'INFRAESTRUTURA DE TI',
            'descricao' => 'INFRAESTRUTURA DE TI',
            'publicacao_categoria_id' => $id_comunicacao,
            'icone' => 'angle-double-right',
            'ordem' => 470,
            ],
        ]);
    }
}