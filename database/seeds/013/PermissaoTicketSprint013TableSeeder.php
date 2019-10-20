<?php

use Illuminate\Database\Seeder;

class PermissaoTicketSprint013TableSeeder extends Seeder
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
                'descricao' => 'CAMPOS ADICIONAIS VISUALIZAR',
                'identificador' => 'CONFIGURACAO_TICKET_CAMPOS_VISUALIZAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'CAMPOS ADICIONAIS CADASTRAR',
                'identificador' => 'CONFIGURACAO_TICKET_CAMPOS_CADASTRAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'CAMPOS ADICIONAIS EDITAR',
                'identificador' => 'CONFIGURACAO_TICKET_CAMPOS_EDITAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'CAMPOS ADICIONAIS EXCLUIR',
                'identificador' => 'CONFIGURACAO_TICKET_CAMPOS_EXCLUIR'
            ],
             [
                'menu_id' => 16,
                'descricao' => 'CATEGORIAS VISUALIZAR',
                'identificador' => 'CONFIGURACAO_TICKET_CATEGORIAS_VISUALIZAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'CATEGORIAS CADASTRAR',
                'identificador' => 'CONFIGURACAO_TICKET_CATEGORIAS_CADASTRAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'CATEGORIAS EDITAR',
                'identificador' => 'CONFIGURACAO_TICKET_CATEGORIAS_EDITAR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'CATEGORIAS EXCLUIR',
                'identificador' => 'CONFIGURACAO_TICKET_CATEGORIAS_EXCLUIR'
            ],
            [
                'menu_id' => 16,
                'descricao' => 'CONFIGURAR TICKETS',
                'identificador' => 'CONFIGURACAO_TICKET_VISUALIZAR'
            ],
            [
                'menu_id' => 19,
                'descricao' => 'VISUALIZAR TICKETS PRÓPRIOS',
                'identificador' => 'TICKETS_VISUALIZAR_PROPRIO'
            ],
            [
                'menu_id' => 19,
                'descricao' => 'VISUALIZAR TICKETS DO DEPARTAMENTO',
                'identificador' => 'TICKETS_VISUALIZAR_DEPARTAMENTO'
            ],
            [
                'menu_id' => 19,
                'descricao' => 'VISUALIZAR TODOS OS TICKETS',
                'identificador' => 'TICKETS_VISUALIZAR_TODOS'
            ],
            [
                'menu_id' => 19,
                'descricao' => 'EXPORTAR DADOS DOS TICKETS',
                'identificador' => 'TICKETS_EXPORTAR'
            ],
            [
                'menu_id' => 19,
                'descricao' => 'RESPONDER TICKETS',
                'identificador' => 'TICKETS_RESPONDER'
            ],
            [
                'menu_id' => 18,
                'descricao' => 'CRIAR TICKETS PRÓPRIOS',
                'identificador' => 'TICKETS_CRIAR_PROPRIO'
            ],
            [
                'menu_id' => 18,
                'descricao' => 'CRIAR TICKETS PARA OUTROS USUÁRIOS',
                'identificador' => 'TICKETS_CRIAR_OUTROS'
            ],
            [
                'menu_id' => 19,
                'descricao' => 'EDITAR INFORMAÇÕES DOS TICKETS',
                'identificador' => 'TICKETS_EDITAR'
            ]
        ]);

       DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 9,
                'permissao_id' => 43
            ],
            [
                'acesso_id' => 9,
                'permissao_id' => 44
            ],
            [
                'acesso_id' => 9,
                'permissao_id' => 45
            ],
            [
                'acesso_id' => 9,
                'permissao_id' => 46
            ],
            [
                'acesso_id' => 9,
                'permissao_id' => 47
            ],
            [
                'acesso_id' => 9,
                'permissao_id' => 48
            ],
            [
                'acesso_id' => 9,
                'permissao_id' => 49
            ],
            [
                'acesso_id' => 9,
                'permissao_id' => 50
            ],
            [
                'acesso_id' => 9,
                'permissao_id' => 51
            ],
            [
                'acesso_id' => 5,
                'permissao_id' => 52
            ],
            [
                'acesso_id' => 10,
                'permissao_id' => 53
            ],
            [
                'acesso_id' => 11,
                'permissao_id' => 54
            ],
            [
                'acesso_id' => 12,
                'permissao_id' => 55
            ],
            [
                'acesso_id' => 10,
                'permissao_id' => 56
            ],
            [
                'acesso_id' => 5,
                'permissao_id' => 57
            ],
            [
                'acesso_id' => 10,
                'permissao_id' => 58
            ],
            [
                'acesso_id' => 13,
                'permissao_id' => 59
            ]
        ]);                 
    }
}
