<?php

use Illuminate\Database\Seeder;

class TicketsAcaoTableSeeder extends Seeder
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

        // Busca ID do departamento
        $departamento_id = DB::table('departamentos')->where('nome', 'INFRAESTRUTURA DE TI')->get()->first()->id;

        // Deleta as ações atuais do departamento
        DB::table('tickets_acao')->where('departamento_id', '=', $departamento_id)->delete();

        // Busca os status
        $_novo = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','NOVO')->get()->first()->id;
        $_aberto = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','ABERTO')->get()->first()->id;
        $_aguardando = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','AGUARDANDO')->get()->first()->id;
        $_resolvido = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','RESOLVIDO')->get()->first()->id;
        $_fechado = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','FECHADO')->get()->first()->id;
        $_erro = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','ERRO')->get()->first()->id;

        // Insere as novas ações
        DB::table('tickets_acao')->insert([
            [
                'nome' => 'Categorizar',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 110,
                'campos' => json_encode(['categoria','responsavel','prioridade','campos_adicionais','solicitante']),
                'status_atual' => json_encode([$_novo]),
                'status_novo' => json_encode([$_aberto,$_erro]),
                'solicitante_executa' => false,
                'responsavel_executa' => false,
                'trata_executa' => true,
                'interacao' => true,
                'nota_interna' => true,
                'icone' => 'fa-list-ul',
            ],
            [
                'nome' => 'Tratativa',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 120,
                'campos' => json_encode(['responsavel','prioridade','campos_adicionais']),
                'status_atual' => json_encode([$_aberto,$_aguardando]),
                'status_novo' => json_encode([$_aberto,$_aguardando]),
                'solicitante_executa' => false,
                'responsavel_executa' => false,
                'trata_executa' => true,
                'interacao' => true,
                'nota_interna' => true,
                'icone' => 'fa-reply',
            ],
            [
                'nome' => 'Responsável',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 130,
                'campos' => json_encode(['responsavel']),
                'status_atual' => json_encode([$_aberto,$_aguardando,$_resolvido]),
                'status_novo' => json_encode([]),
                'solicitante_executa' => false,
                'responsavel_executa' => true,
                'trata_executa' => true,
                'interacao' => true,
                'nota_interna' => true,
                'icone' => 'fa-user',
            ],
            [
                'nome' => 'Previsão',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 140,
                'campos' => json_encode(['data_previsao']),
                'status_atual' => json_encode([$_aberto,$_aguardando,$_resolvido]),
                'status_novo' => json_encode([]),
                'solicitante_executa' => false,
                'responsavel_executa' => false,
                'trata_executa' => true,
                'interacao' => true,
                'nota_interna' => true,
                'icone' => 'fa-calendar',
            ],
            [
                'nome' => 'Agendar notificação',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 150,
                'campos' => json_encode(['data_notificacao','responsavel']),
                'status_atual' => json_encode([$_aberto,$_aguardando,$_resolvido]),
                'status_novo' => json_encode([]),
                'solicitante_executa' => false,
                'responsavel_executa' => false,
                'trata_executa' => true,
                'interacao' => false,
                'nota_interna' => true,
                'icone' => 'fa-bell',
            ],
            [
                'nome' => 'Resolvido',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 160,
                'campos' => json_encode(['campos_adicionais']),
                'status_atual' => json_encode([$_aberto,$_aguardando]),
                'status_novo' => json_encode([$_resolvido]),
                'solicitante_executa' => false,
                'responsavel_executa' => false,
                'trata_executa' => true,
                'interacao' => true,
                'nota_interna' => false,
                'icone' => 'fa-check-square-o',
            ],
            [
                'nome' => 'Avaliar',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 170,
                'campos' => json_encode(['avaliacao']),
                'status_atual' => json_encode([$_resolvido]),
                'status_novo' => json_encode([$_fechado,$_aberto]),
                'solicitante_executa' => true,
                'responsavel_executa' => false,
                'trata_executa' => false,
                'interacao' => true,
                'nota_interna' => false,
                'icone' => 'fa-star-half-o',
            ],
            [
                'nome' => 'Fechar',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 180,
                'campos' => json_encode(['campos_adicionais']),
                'status_atual' => json_encode([$_novo,$_aberto,$_aguardando,$_resolvido]),
                'status_novo' => json_encode([$_fechado]),
                'solicitante_executa' => true,
                'responsavel_executa' => false,
                'trata_executa' => true,
                'interacao' => true,
                'nota_interna' => false,
                'icone' => 'fa-flag-checkered',
            ],
            [
                'nome' => 'Reabrir',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => 190,
                'campos' => json_encode([]),
                'status_atual' => json_encode([$_fechado]),
                'status_novo' => json_encode([$_novo]),
                'solicitante_executa' => true,
                'responsavel_executa' => false,
                'trata_executa' => true,
                'interacao' => true,
                'nota_interna' => false,
                'icone' => 'fa-undo',
            ],
        ]);
    }
}
