<?php

use Illuminate\Database\Seeder;

class PermissaoTicketSprint016TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
            
       DB::table('permissoes')->insert([
            [
                'menu_id' => 16,
                'descricao' => 'AÇÃO VISUALIZAR',
                'identificador' => 'CONFIGURACAO_TICKET_ACOES_VISUALIZAR'
                                     
            ],
            [
                'menu_id' => 16,
                'descricao' => 'AÇÃO CADASTRAR',
                'identificador' => 'CONFIGURACAO_TICKET_ACOES_CADASTRAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'AÇÃO EDITAR',
                'identificador' => 'CONFIGURACAO_TICKET_ACOES_EDITAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'AÇÃO EXCLUIR',
                'identificador' => 'CONFIGURACAO_TICKET_ACOES_EXCLUIR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'GATILHO VISUALIZAR',
                'identificador' => 'CONFIGURACAO_TICKET_GATILHOS_VISUALIZAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'GATILHO CADASTRAR',
                'identificador' => 'CONFIGURACAO_TICKET_GATILHOS_CADASTRAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'GATILHO EDITAR',
                'identificador' => 'CONFIGURACAO_TICKET_GATILHOS_EDITAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'GATILHO EXCLUIR',
                'identificador' => 'CONFIGURACAO_TICKET_GATILHOS_EXCLUIR'
            ]
          
        ]);

            $permissao_acao_visualizar = DB::table('permissoes')->where('identificador', 'CONFIGURACAO_TICKET_ACOES_VISUALIZAR')->get()->first()->id;
            $permissao_acao_cadastrar = DB::table('permissoes')->where('identificador', 'CONFIGURACAO_TICKET_ACOES_CADASTRAR')->get()->first()->id;
            $permissao_acao_editar = DB::table('permissoes')->where('identificador', 'CONFIGURACAO_TICKET_ACOES_EDITAR')->get()->first()->id;
            $permissao_acao_excluir = DB::table('permissoes')->where('identificador', 'CONFIGURACAO_TICKET_ACOES_EXCLUIR')->get()->first()->id;
            $permissao_gatilho_visualizar = DB::table('permissoes')->where('identificador', 'CONFIGURACAO_TICKET_GATILHOS_VISUALIZAR')->get()->first()->id;
            $permissao_gatilho_cadastrar = DB::table('permissoes')->where('identificador', 'CONFIGURACAO_TICKET_GATILHOS_CADASTRAR')->get()->first()->id;
            $permissao_gatilho_editar = DB::table('permissoes')->where('identificador', 'CONFIGURACAO_TICKET_GATILHOS_EDITAR')->get()->first()->id;
            $permissao_gatilho_excluir = DB::table('permissoes')->where('identificador', 'CONFIGURACAO_TICKET_GATILHOS_EXCLUIR')->get()->first()->id;
        
            $acesso_id = DB::table('acessos')->where('nome', 'CONFIGURAÇÕES - CONFIGURAR TICKETS')->get()->first()->id;


       

       DB::table('acesso_permissao')->insert([
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_acao_visualizar
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_acao_cadastrar
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_acao_editar
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_acao_excluir
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_gatilho_visualizar
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_gatilho_cadastrar
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_gatilho_editar
            ],
            [
                'acesso_id' =>  $acesso_id,
                'permissao_id' => $permissao_gatilho_excluir
            ]
        ]);        
    }
}




