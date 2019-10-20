<?php

use Illuminate\Database\Seeder;

class PermissaoBaseDeConhecimentoSprint018TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Busca ID do menu BASE DE CONHECIMENTO
        $menu_id = DB::table('menus')->where('nome', 'PUBLICAÇÕES')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'VISUALIZAR PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_VISUALIZAR'
            ],
            [
                'menu_id' => $menu_id,
                'descricao' => 'CADASTRAR PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_CADASTRAR'
            ],
            [
                'menu_id' => $menu_id,
                'descricao' => 'EDITAR PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_EDITAR'
            ],
            // [
            //     'menu_id' => $menu_id,
            //     'descricao' => 'CADASTRAR CATEGORIAS',
            //     'identificador' => 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR'
            // ],
            // [
            //     'menu_id' => $menu_id,
            //     'descricao' => 'EDITAR CATEGORIAS',
            //     'identificador' => 'BASE_PUBLICACOES_CATEGORIA_EDITAR'
            // ],
            // [
            //     'menu_id' => $menu_id,
            //     'descricao' => 'EXCLUIR CATEGORIAS',
            //     'identificador' => 'BASE_PUBLICACOES_CATEGORIA_EXCLUIR'
            // ],
            [
                'menu_id' => $menu_id,
                'descricao' => 'ALTERAR CONTEÚDO DAS PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_CONTEUDO_EDITAR'
            ],
            // [
            //     'menu_id' => $menu_id,
            //     'descricao' => 'ATIVAR OU INATIVAR AS PUBLICAÇÕES',
            //     'identificador' => 'BASE_PUBLICACOES_ATIVAR'
            // ],                 
        ]);

        DB::table('acessos')->insert([
            [
                'nome' => 'BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES'
            ]
        ]);                                         

        $permissao_publicacoes_visualizar = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_VISUALIZAR')->get()->first()->id;
        $permissao_publicacoes_cadastrar = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CADASTRAR')->get()->first()->id;
        $permissao_publicacoes_editar = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_EDITAR')->get()->first()->id;
        // $permissao_publicacoes_excluir = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR')->get()->first()->id;
        // $permissao_categoria_publicacoes_editar = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_EDITAR')->get()->first()->id;
        // $permissao_categoria_publicacoes_excluir = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CATEGORIA_EXCLUIR')->get()->first()->id;
        $permissao_categoria_conteudo_editar = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_CONTEUDO_EDITAR')->get()->first()->id;
        // $permissao_publicacoes_ativar = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_ATIVAR')->get()->first()->id;
    
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES')->get()->first()->id;
        $acesso_basico = DB::table('acessos')->where('nome', 'ACESSO BÁSICO')->get()->first()->id;

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_publicacoes_visualizar
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_publicacoes_cadastrar
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_publicacoes_editar
            ],
            // [
            //     'acesso_id' =>  $acesso_id,
            //     'permissao_id' => $permissao_publicacoes_excluir
            // ],
            // [
            //     'acesso_id' =>  $acesso_id,
            //     'permissao_id' => $permissao_categoria_publicacoes_editar
            // ],
            // [
            //     'acesso_id' =>  $acesso_id,
            //     'permissao_id' => $permissao_categoria_publicacoes_excluir
            // ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_categoria_conteudo_editar
            ],
            // [
            //     'acesso_id' =>  $acesso_id,
            //     'permissao_id' => $permissao_publicacoes_ativar
            // ],
            [
                'acesso_id' =>  $acesso_basico,
                'permissao_id' => $permissao_publicacoes_visualizar
            ]
        ]);             
    }
}
