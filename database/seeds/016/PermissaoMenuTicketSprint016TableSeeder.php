<?php

use Illuminate\Database\Seeder;

class PermissaoMenuTicketSprint016TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        // Busca por id em perfis
        $administrador_id       = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $infra_de_ti_id         = DB::table('perfis')->where('nome', 'INFRAESTRUTURA DE TI')->get()->first()->id;
        $infra_de_ti_gestor_id  = DB::table('perfis')->where('nome', 'INFRAESTRUTURA DE TI - GESTOR')->get()->first()->id;
        $adm_e_fin_id           = DB::table('perfis')->where('nome', 'ADMINISTRATIVO E FINANCEIRO')->get()->first()->id;
        $dev_sist_gestor_id     = DB::table('perfis')->where('nome', 'DESENVOLVIMENTO DE SISTEMAS - GESTOR')->get()->first()->id;
        $gestao_pessoas_id      = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        $gestao_conhecimento_id = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;
        $tela_monitoramento_id  = DB::table('perfis')->where('nome', 'TELA DE MONITORAMENTO')->get()->first()->id;

        $acesso_id = DB::table('acessos')->where('nome', 'TICKETS - VISUALIZAR DASHBOARD')->get()->first()->id;

        DB::table('permissoes')->insert([
            [
                'menu_id' => 21,
                'descricao' => 'VISUALIZAR DASHBOARD DE TICKETS',
                'identificador' => 'TICKET_VISUALIZAR_DASHBOARD'
            ]          
        ]);

        $permissao_id = DB::table('permissoes')->where('identificador', 'TICKET_VISUALIZAR_DASHBOARD')->get()->first()->id;

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => $acesso_id,
                'permissao_id' => $permissao_id
            ]
        ]);

        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $administrador_id
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $infra_de_ti_id
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $infra_de_ti_gestor_id
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $adm_e_fin_id
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $dev_sist_gestor_id
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $gestao_pessoas_id
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $gestao_conhecimento_id
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $tela_monitoramento_id
            ]

        ]);      
    }
}




