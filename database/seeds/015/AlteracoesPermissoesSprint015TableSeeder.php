<?php

use Illuminate\Database\Seeder;

class AlteracoesPermissoesSprint015TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Busca ID do usuário GUSTAVO LOPES
        $usuario_id = DB::table('usuarios')->where('nome', 'GUSTAVO LOPES')->first()->id;
        
        // Alterar nome do acesso TICKETS - EDITAR INFORMAÇÕES DOS TICKETS
        DB::table('acessos')->where('nome', 'TICKETS - EDITAR INFORMAÇÕES DOS TICKETS')->update(['nome' => 'TICKETS - EDITAR TICKETS DO DEPARTAMENTO']);

        // Adicionado o GESTOR no final do nome do atual perfil INTRAESTRUTURA DE TI
        DB::table('perfis')->where('nome', '=', 'INFRAESTRUTURA DE TI')->update(['nome' => 'INFRAESTRUTURA DE TI - GESTOR']);

        // Criado o novo perfil de INFRAESTRUTURA DE TI
        DB::table('perfis')->insert([
            [
                'nome' => 'INFRAESTRUTURA DE TI',
                'usuario_inclusao_id' => $usuario_id,
            ]
        ]);
        $_perfil_infra = DB::table('perfis')->where('nome', 'INFRAESTRUTURA DE TI')->get()->first()->id;

        // Resgatar id dos atuais acessos
        $_acesso_basico = DB::table('acessos')->where('nome', 'ACESSO BÁSICO')->get()->first()->id;
        $_acesso_usuarios = DB::table('acessos')->where('nome', 'CONFIGURAÇÕES - CONFIGURAR USUÁRIOS')->get()->first()->id;
        $_acesso_tratar = DB::table('acessos')->where('nome', 'TICKETS - TRATAR TICKETS DO DEPARTAMENTO')->get()->first()->id;
        $_acesso_editar = DB::table('acessos')->where('nome', 'TICKETS - EDITAR TICKETS DO DEPARTAMENTO')->get()->first()->id;
        $_acesso_gerenciar = DB::table('acessos')->where('nome', 'TICKETS - GERENCIAR TICKETS DO DEPARTAMENTO')->get()->first()->id;
        
        // Vincular o perfil INFRAESTRUTURA DE TI com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $_acesso_basico,
                'perfil_id' => $_perfil_infra,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_usuarios,
                'perfil_id' => $_perfil_infra,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_tratar,
                'perfil_id' => $_perfil_infra,
                'usuario_inclusao_id' => $usuario_id,
            ],
        ]);

        // Alterar o usuário do DENER para o novo perfil de INFRAESTRUTURA DE TI
        DB::table('usuarios')->where('nome', '=', 'DÊNER OLIVEIRA')->update(['perfil_id' => $_perfil_infra]);


        // Criado o novo perfil de GESTÃO DO CONHECIMENTO
        DB::table('perfis')->insert([
            [
                'nome' => 'GESTÃO DO CONHECIMENTO',
                'usuario_inclusao_id' => $usuario_id,
            ]
        ]);
        $_perfil_gc = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;
        
        // Vincular o perfil GESTÃO DO CONHECIMENTO com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $_acesso_basico,
                'perfil_id' => $_perfil_gc,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_tratar,
                'perfil_id' => $_perfil_gc,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_editar,
                'perfil_id' => $_perfil_gc,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_gerenciar,
                'perfil_id' => $_perfil_gc,
                'usuario_inclusao_id' => $usuario_id,
            ],
        ]);

        // Alterar o usuário da SOFIE para o novo perfil de GESTÃO DO CONHECIMENTO
        DB::table('usuarios')->where('nome', '=', 'SOFIE PAPAIS')->update(['perfil_id' => $_perfil_gc]);


        // Criado o novo perfil de ADMINISTRATIVO E FINANCEIRO
        DB::table('perfis')->insert([
            [
                'nome' => 'ADMINISTRATIVO E FINANCEIRO',
                'usuario_inclusao_id' => $usuario_id,
            ]
        ]);
        $_perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRATIVO E FINANCEIRO')->get()->first()->id;
        
        // Vincular o perfil GESTÃO DO CONHECIMENTO com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $_acesso_basico,
                'perfil_id' => $_perfil_adm,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_tratar,
                'perfil_id' => $_perfil_adm,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_editar,
                'perfil_id' => $_perfil_adm,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_gerenciar,
                'perfil_id' => $_perfil_adm,
                'usuario_inclusao_id' => $usuario_id,
            ],
        ]);

        // Alterar o usuário da SOFIE para o novo perfil de GESTÃO DO CONHECIMENTO
        DB::table('usuarios')->where('nome', '=', 'ERICA OLIVEIRA')->update(['perfil_id' => $_perfil_adm]);
        DB::table('usuarios')->where('nome', '=', 'MARCIA OLIVEIRA')->update(['perfil_id' => $_perfil_adm]);


        // Criado o novo perfil de DESENVOLVIMENTO DE SISTEMAS
        DB::table('perfis')->insert([
            [
                'nome' => 'DESENVOLVIMENTO DE SISTEMAS - GESTOR',
                'usuario_inclusao_id' => $usuario_id,
            ]
        ]);
        $_perfil_desenv = DB::table('perfis')->where('nome', 'DESENVOLVIMENTO DE SISTEMAS - GESTOR')->get()->first()->id;
        
        // Vincular o perfil GESTÃO DO CONHECIMENTO com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $_acesso_basico,
                'perfil_id' => $_perfil_desenv,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_tratar,
                'perfil_id' => $_perfil_desenv,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_editar,
                'perfil_id' => $_perfil_desenv,
                'usuario_inclusao_id' => $usuario_id,
            ],
            [
                'acesso_id' => $_acesso_gerenciar,
                'perfil_id' => $_perfil_desenv,
                'usuario_inclusao_id' => $usuario_id,
            ],
        ]);

        // Alterar o usuário da SOFIE para o novo perfil de GESTÃO DO CONHECIMENTO
        DB::table('usuarios')->where('nome', '=', 'JOÃO DAVID')->where('id', '!=', '4')->update(['perfil_id' => $_perfil_desenv]);



        // Criado o novo perfil de TELA DE MONITORAMENTO
        DB::table('perfis')->insert([
            [
                'nome' => 'TELA DE MONITORAMENTO',
                'usuario_inclusao_id' => $usuario_id,
            ]
        ]);
        $_perfil_monitor = DB::table('perfis')->where('nome', 'TELA DE MONITORAMENTO')->get()->first()->id;
        $_acesso_todos = DB::table('acessos')->where('nome', 'TICKETS - VISUALIZAR TODOS OS TICKETS')->get()->first()->id;
        
        // Vincular o perfil GESTÃO DO CONHECIMENTO com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $_acesso_todos,
                'perfil_id' => $_perfil_monitor,
                'usuario_inclusao_id' => $usuario_id,
            ],
        ]);

        // Cria o novo usuário
        DB::table('usuarios')->insert([
            [
                'perfil_id' => $_perfil_monitor,
                'nome' => 'TELA DE MONITORAMENTO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'monitoramento',
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false,
                'usuario_inclusao_id' => $usuario_id,
            ],
        ]);

    }
}
