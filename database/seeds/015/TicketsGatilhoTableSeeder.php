<?php

use Illuminate\Database\Seeder;

class TicketsGatilhoTableSeeder extends Seeder
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

        // Busca ID do usuário GERSON PIRES
        $_gerson = DB::table('usuarios')->where('nome', 'GERSON PIRES')->first()->id;

        // Busca ID do departamento
        $departamento_id = DB::table('departamentos')->where('nome', 'INFRAESTRUTURA DE TI')->get()->first()->id;

        // Deleta as ações atuais do departamento
        DB::table('tickets_gatilho')->where('departamento_id', '=', $departamento_id)->delete();

        // Busca os status
        $_novo = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','NOVO')->get()->first()->id;
        $_aberto = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','ABERTO')->get()->first()->id;
        $_aguardando = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','AGUARDANDO')->get()->first()->id;
        $_resolvido = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','RESOLVIDO')->get()->first()->id;
        $_fechado = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','FECHADO')->get()->first()->id;
        $_erro = DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->where('nome','ERRO')->get()->first()->id;

        // Insere os gatilhos para INFRAESTRUTURA DE TI
        DB::table('tickets_gatilho')->insert([
            [
                'nome' => 'Notificação ao abrir ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '110',
                'ativo' => true,
                'quanto_executar' => json_encode([
                    'status' => $_novo
                ]),
                'acao' => json_encode([
                    'notificacao' => [
                        'solicitante' => false,
                        'responsavel' => false,
                        'departamento' => [ '1' => $departamento_id ],
                        'cargo' => false,
                        'usuario' => false,
                        'mensagem' => 'Novo ticket'
                    ]    
                ]),
            ],
            [
                'nome' => 'Notificação ao se tornar responsável do ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '120',
                'ativo' => false,
                'quanto_executar' => json_encode([
                    'responsavel' => 'mudou'
                ]),
                'acao' => json_encode([
                    'notificacao' => [
                        'solicitante' => false,
                        'responsavel' => true,
                        'departamento' => false,
                        'cargo' => false,
                        'usuario' => false,
                        'mensagem' => 'Responsável'
                    ]    
                ]),
            ],
            [
                'nome' => 'Preencher responsável ao abrir o ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '130',
                'ativo' => false,
                'quanto_executar' => json_encode([
                    'status' => $_novo
                ]),
                'acao' => json_encode([
                    'responsavel' => $_gerson   
                ]),
            ],
            [
                'nome' => 'Gerar notificação ao preencher data de notificação',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '140',
                'ativo' => true,
                'quanto_executar' => json_encode([
                    'dt_notificacao' => 'mudou'
                ]),
                'acao' => json_encode([
                    'notificacao' => [
                        'solicitante' => false,
                        'responsavel' => true,
                        'departamento' => false,
                        'cargo' => false,
                        'usuario' => false,
                        'mensagem' => 'Notificação'
                    ]   
                ]),
            ],
            [
                'nome' => 'Atualiza data de resolução do ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '210',
                'ativo' => true,
                'quanto_executar' => json_encode([
                    'status' => $_resolvido
                ]),
                'acao' => json_encode([
                    'dt_resolucao' => 'now()'
                ]),
            ],
            [
                'nome' => 'Atualiza data de fechamento do ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '220',
                'ativo' => true,
                'quanto_executar' => json_encode([
                    'status' => $_fechado
                ]),
                'acao' => json_encode([
                    'dt_fechamento' => 'now()'
                ]),
            ],
            [
                'nome' => 'Limpa data de notificação ao fechar o ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '310',
                'ativo' => true,
                'quanto_executar' => json_encode([
                    'status' => $_fechado
                ]),
                'acao' => json_encode([
                    'dt_notificacao' => 'null',
                ]),
            ],
            [
                'nome' => 'Limpa data de previsão ao fechar o ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '320',
                'ativo' => true,
                'quanto_executar' => json_encode([
                    'status' => $_fechado
                ]),
                'acao' => json_encode([
                    'dt_previsao' => 'null',
                ]),
            ],
            [
                'nome' => 'Limpa data de resolução ao abrir o ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '330',
                'ativo' => true,
                'quanto_executar' => json_encode([
                    'status' => $_novo
                ]),
                'acao' => json_encode([
                    'dt_resolucao' => 'null',
                ]),
            ],
            [
                'nome' => 'Limpa data de fechamento ao abrir o ticket',
                'departamento_id' => $departamento_id,
                'usuario_inclusao_id' => $usuario_id,
                'ordem' => '340',
                'ativo' => true,
                'quanto_executar' => json_encode([
                    'status' => $_novo
                ]),
                'acao' => json_encode([
                    'dt_fechamento' => 'null',
                ]),
            ],


        ]);

    }
}
