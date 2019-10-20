<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
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
                'menu_id' => NULL,
                'nome' => 'HOME',
                'descricao' => 'PÁGINA INICIAL',
                'ativo' => 'TRUE',
                'icone' => 'home',
                'url' => '/',
                'nivel' => '1',
                'ordem' => 1110
            ],
            [
                'menu_id' => NULL,
                'nome' => 'CONFIGURAÇÕES',
                'descricao' => 'CONFIGURAÇÕES DO SISTEMA',
                'ativo' => 'TRUE',
                'icone' => 'cog',
                'url' => '',
                'nivel' => '1',
                'ordem' => 1120
            ],
            [
                'menu_id' => 2,
                'nome' => 'ACESSOS',
                'descricao' => 'CONFIGURAÇÃO DE ACESSOS',
                'ativo' => 'TRUE',
                'icone' => 'eye',
                'url' => '/configuracao/acesso',
                'nivel' => '2',
                'ordem' => 110
            ],
            [
                'menu_id' => 2,
                'nome' => 'PERFIS',
                'descricao' => 'CONFIGURAÇÃO DE PERFIL',
                'ativo' => 'TRUE',
                'icone' => 'users',
                'url' => '/configuracao/perfil',
                'nivel' => '2',
                'ordem' => 120
            ],
            [
                'menu_id' => 2,
                'nome' => 'USUÁRIOS',
                'descricao' => 'CONFIGURAÇÃO DE USUÁRIO',
                'ativo' => 'TRUE',
                'icone' => 'user',
                'url' => '/configuracao/usuario',
                'nivel' => '2',
                'ordem' => 130
            ],
            [
                'menu_id' => NULL,
                'nome' => 'PESSOAS',
                'descricao' => 'GESTÃO DE PESSOAS',
                'ativo' => 'TRUE',
                'icone' => 'id-card-o',
                'url' => '',
                'nivel' => '1',
                'ordem' => 1130
            ],
            [
                'menu_id' => 6,
                'nome' => 'FUNCIONÁRIOS',
                'descricao' => 'CADASTRO DE FUNCIONÁRIO',
                'ativo' => 'TRUE',
                'icone' => 'user',
                'url' => '/rh/funcionario',
                'nivel' => '2',
                'ordem' => 110
            ],
            [
                'menu_id' => 9,
                'nome' => 'PARÂMETROS',
                'descricao' => 'PARÂMETROS DO SISTEMA',
                'ativo' => 'TRUE',
                'icone' => 'list-alt',
                'url' => '/configuracao/sistema/parametro',
                'nivel' => '3',
                'ordem' => 10
            ],
            [
                'menu_id' => 2,
                'nome' => 'SISTEMA',
                'descricao' => 'TELAS DE CONFIGURAÇÃO DO SISTEMA',
                'ativo' => 'TRUE',
                'icone' => 'server',
                'url' => '',
                'nivel' => '2',
                'ordem' => 140
            ],
            [
                'menu_id' => NULL,
                'nome' => 'LOG',
                'descricao' => 'MENU DE LOGS DO SISTEMA',
                'ativo' => 'TRUE',
                'icone' => 'database',
                'url' => '',
                'nivel' => '1',
                'ordem' => 1150
            ],
            [
                'menu_id' => 10,
                'nome' => 'LOG DE ACESSOS',
                'descricao' => 'VISUALIZAR LOGINS E LOGOUTS DO SISTEMA',
                'ativo' => 'TRUE',
                'icone' => 'sign-in',
                'url' => '/log/acessos',
                'nivel' => '2',
                'ordem' => 110
            ],
            [
                'menu_id' => 9,
                'nome' => 'POLÍTICA DE SENHAS',
                'descricao' => 'TELA DE CONFIGURAÇÃO DA POLÍTICA DE SENHAS',
                'ativo' => 'TRUE',
                'icone' => 'lock',
                'url' => '/configuracao/sistema/politica_senhas',
                'nivel' => '3',
                'ordem' => 20
            ]
        ]);
    }
}
