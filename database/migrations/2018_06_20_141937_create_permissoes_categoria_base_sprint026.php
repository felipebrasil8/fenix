<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissoesCategoriaBaseSprint026 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu_id = DB::table('menus')->where('nome', 'PUBLICAÇÕES')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'CADASTRAR CATEGORIAS',
                'identificador' => 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR'
            ],
            [
                'menu_id' => $menu_id,
                'descricao' => 'EDITAR CATEGORIAS',
                'identificador' => 'BASE_PUBLICACOES_CATEGORIA_EDITAR'
            ],
            [
                'menu_id' => $menu_id,
                'descricao' => 'ORDENAR CATEGORIAS',
                'identificador' => 'BASE_PUBLICACOES_CATEGORIA_ORDENAR'
            ]
        ]);

        DB::table('acessos')->insert([
            [
                'nome' => 'BASE DE CONHECIMENTO - GERENCIAR CATEGORIAS'
            ]
        ]); 

        $permissao[0] = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR')->get()->first()->id;
        $permissao[1] = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_EDITAR')->get()->first()->id;
        $permissao[2] = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_ORDENAR')->get()->first()->id;
       
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR CATEGORIAS')->get()->first()->id;

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao[0]
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao[1]
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao[2]
            ]
        ]);      

        $perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_gp = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        $perfil_gc = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;
        
        // Vincular perfis com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_adm
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_gp
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_gc
            ],
        ]);

        DB::table('icones')->insert([
            [
                'icone' => 'fa-home',
                'unicode' => '&#xf015;',
                'nome' => 'HOME'
            ],
            [
                'icone' => 'fa-question-circle',
                'unicode' => '&#xf059;',
                'nome' => 'INTERROGAÇÃO CIRCULO'
            ],
            [
                'icone' => 'fa-map-signs',
                'unicode' => '&#xf277;',
                'nome' => 'SINAIS DE MAPA'
            ],
            [
                'icone' => 'fa-book',
                'unicode' => '&#xf02d;',
                'nome' => 'LIVRO'
            ],
            [
                'icone' => 'fa-sitemap',
                'unicode' => '&#xf0e8;',
                'nome' => 'MAPA DO SITE'
            ],
            [
                'icone' => 'fa-bullhorn',
                'unicode' => '&#xf0a1;',
                'nome' => 'MEGAFONE'
            ],
            [
                'icone' => 'fa-link',
                'unicode' => '&#xf0c1;',
                'nome' => 'LINK'
            ],
            [
                'icone' => 'fa-angle-double-right',
                'unicode' => '&#xf101;',
                'nome' => 'ÂNGULO DUPLO DIREITO'
            ]
        ]);

        $question = DB::table('icones')->where('icone', 'fa-question')->get();
        $comment  = DB::table('icones')->where('icone', 'fa-comment')->get();

        if(count($question) == 0)
        {
            DB::table('icones')->insert([
                [
                    'icone' => 'fa-question',
                    'unicode' => '&#xf075;',
                    'nome' => 'INTERROGAÇÃO'
                ]
            ]);
        }

        if(count($comment) == 0)
        {
            DB::table('icones')->insert([
                [
                    'icone' => 'fa-comment',
                    'unicode' => '&#xf128;',
                    'nome' => 'COMENTÁRIO'
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR CATEGORIAS')->get()->first()->id;
        $permissao[0] = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR')->get()->first()->id;
        $permissao[1] = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_EDITAR')->get()->first()->id;
        $permissao[2] = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_ORDENAR')->get()->first()->id;

        DB::table('acesso_permissao')->where('permissao_id', $permissao[0])->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao[1])->delete();
        DB::table('acesso_permissao')->where('permissao_id', $permissao[2])->delete();
        DB::table('acesso_perfil')->where('acesso_id', $acesso_id)->delete();
        DB::table('acessos')->where('id', $acesso_id)->delete();
        DB::table('permissoes')->where('id', $permissao[0])->delete();
        DB::table('permissoes')->where('id', $permissao[1])->delete();
        DB::table('permissoes')->where('id', $permissao[2])->delete();

        DB::table('icones')->where('icone', 'fa-home')->delete();
        DB::table('icones')->where('icone', 'fa-question-circle')->delete();
        DB::table('icones')->where('icone', 'fa-map-signs')->delete();
        DB::table('icones')->where('icone', 'fa-book')->delete();
        DB::table('icones')->where('icone', 'fa-sitemap')->delete();
        DB::table('icones')->where('icone', 'fa-bullhorn')->delete();
        DB::table('icones')->where('icone', 'fa-link')->delete();
        DB::table('icones')->where('icone', 'fa-question')->delete();
        DB::table('icones')->where('icone', 'fa-comment')->delete();
        DB::table('icones')->where('icone', 'fa-angle-double-right')->delete();
    }
}
