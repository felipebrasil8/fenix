<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRascunhoSprint024 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         *  Cria permissoes de rascunho
         */
        // Busca ID do menu BASE DE CONHECIMENTO
        $menu_id = DB::table('menus')->where('nome', 'PUBLICAÇÕES')->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => $menu_id,
                'descricao' => 'ALTERAR RASCUNHO DAS PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_RASCUNHO'
            ]        
        ]);

        //busca o id de acesso, de BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES')->get()->first()->id;

        //busca o id criado de BASE_PUBLICACOES_RASCUNHO
        $permissao_id = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_RASCUNHO')->get()->first()->id;

        //vincula ao acesso x permissao
        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_id
            ]
        ]);

        /*
         * Adicionar colunas
         */
        Schema::table('publicacoes_colaboradores', function (Blueprint $table) {
            $table->boolean('rascunho')->default(false);
        });

        Schema::table('publicacoes_conteudos', function (Blueprint $table) {
            $table->boolean('rascunho')->default(false);
        });

        Schema::table('publicacoes_tags', function (Blueprint $table) {
            $table->boolean('rascunho')->default(false);
        });

        DB::statement("DROP INDEX Publicacao_tag_unique;");

        DB::statement("CREATE UNIQUE INDEX Publicacao_tag_unique ON publicacoes_tags (tag, publicacao_id, rascunho);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //busca o id de acesso, de BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES')->get()->first()->id;

        //busca o id criado de BASE_PUBLICACOES_RASCUNHO
        $permissao_id = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_RASCUNHO')->get()->first()->id;

        //desvincula ao acesso x permissao
        DB::table('acesso_permissao')->where( 'acesso_id', '=', $acesso_id)
                                        ->where( 'permissao_id', '=', $permissao_id)
                                        ->delete();

        //delete permissao
        DB::table('permissoes')->where( 'identificador', '=', 'BASE_PUBLICACOES_RASCUNHO')->delete();

        Schema::table('publicacoes_colaboradores', function (Blueprint $table) {
            $table->dropColumn('rascunho');
        });

        Schema::table('publicacoes_conteudos', function (Blueprint $table) {
            $table->dropColumn('rascunho');
        });

        Schema::table('publicacoes_tags', function (Blueprint $table) {
            $table->dropColumn('rascunho');
        });

        //Sobreescreve para o anterior (Deletar antes não deu certo)
        DB::statement("CREATE UNIQUE INDEX Publicacao_tag_unique ON publicacoes_tags (tag, publicacao_id);");
    }
}