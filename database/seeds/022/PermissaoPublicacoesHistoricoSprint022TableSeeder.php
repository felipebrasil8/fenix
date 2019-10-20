<?php

use Illuminate\Database\Seeder;

class PermissaoPublicacoesHistoricoSprint022TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //cria a permissao para visualizar historico das publicacoes
        DB::table('permissoes')->insert([
            [
                'menu_id' => 23,
                'descricao' => 'VISUALIZAR HISTÓRICO DAS PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_HISTORICO'
            ],
            [
                'menu_id' => 23,
                'descricao' => 'APAGAR HISTÓRICO DAS PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_HISTORICO_APAGAR'
            ],
            [
                'menu_id' => 23,
                'descricao' => 'ALTERAR RESTRIÇÃO DE ACESSO DAS PUBLICAÇÕES',
                'identificador' => 'BASE_PUBLICACOES_RESTRICAO_EDITAR'
            ]                  

        ]);

	//inclui acesso para alterar permissao de restricao de acesso
        DB::table('acessos')->insert([
            [
                'nome' => 'BASE DE CONHECIMENTO - CONFIGURAR RESTRIÇÃO DE ACESSO'
            ]
        ]);                                         

        //busca o id criado de BASE_PUBLICACOES_EXPORTAR
        $permissao_id = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_HISTORICO')->get()->first()->id;

        $permissao_id_apagar = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_HISTORICO_APAGAR')->get()->first()->id;
       
        $permissao_id_restringir = DB::table('permissoes')->where('identificador', 'BASE_PUBLICACOES_RESTRICAO_EDITAR')->get()->first()->id;

        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - GERENCIAR PUBLICAÇÕES')->get()->first()->id;

        $acesso_id_restricao = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - CONFIGURAR RESTRIÇÃO DE ACESSO')->get()->first()->id;

        //vincula ao acesso x permissao
        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_id
            ],
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_id_apagar
            ],
            [
                'acesso_id' => $acesso_id_restricao,
                'permissao_id' => $permissao_id_restringir
            ]
        ]);

        $perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_gp = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        
        // Vincular perfis com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_id_restricao,
                'perfil_id' => $perfil_adm
            ],
            [
                'acesso_id' => $acesso_id_restricao,
                'perfil_id' => $perfil_gp
            ],
        ]);

    }
}



