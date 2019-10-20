<?php

use Illuminate\Database\Seeder;

class TicketsCategoriaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('tickets_categoria')->insert([
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'ACESSOS',
                'descricao' => 'ACESSOS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'CELULAR',
                'descricao' => 'CELULAR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'COMPUTADOR / NOTEBOOK',
                'descricao' => 'COMPUTADOR / NOTEBOOK',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'IMPRESSORA',
                'descricao' => 'IMPRESSORA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'PERIFÉRICOS',
                'descricao' => 'PERIFÉRICOS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'REDE / INTERNET',
                'descricao' => 'REDE / INTERNET',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'SERVIDOR',
                'descricao' => 'SERVIDOR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'TELA DE MONITORAMENTO',
                'descricao' => 'TELA DE MONITORAMENTO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => NULL,
                'nome' => 'TELEFONIA',
                'descricao' => 'TELEFONIA',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA ACESSO
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'ADMISSÃO',
                'descricao' => 'ADMISSÃO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'CRM',
                'descricao' => 'CRM',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'DEMONSTRAÇÃO',
                'descricao' => 'DEMONSTRAÇÃO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'DESLIGAMENTO',
                'descricao' => 'DESLIGAMENTO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'E-MAIL',
                'descricao' => 'E-MAIL',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'FENIX',
                'descricao' => 'FENIX',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'NXT3000',
                'descricao' => 'NXT3000',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'NXT-DISCADOR',
                'descricao' => 'NXT-DISCADOR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'NXT-IPBX',
                'descricao' => 'NXT-IPBX',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'NXT-URATIVA',
                'descricao' => 'NXT-URATIVA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'OUTROS',
                'descricao' => 'OUTROS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'RAMAL REMOTO',
                'descricao' => 'RAMAL REMOTO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 1,
                'nome' => 'TAREFAS',
                'descricao' => 'TAREFAS',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA CELULAR
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 2,
                'nome' => 'CABO USB',
                'descricao' => 'CABO USB',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 2,
                'nome' => 'CAPA',
                'descricao' => 'CAPA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 2,
                'nome' => 'CARREGADOR',
                'descricao' => 'CARREGADOR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 2,
                'nome' => 'CONFIGURAÇÕES',
                'descricao' => 'CONFIGURAÇÕES',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 2,
                'nome' => 'DEFEITO',
                'descricao' => 'DEFEITO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 2,
                'nome' => 'PELÍCULA',
                'descricao' => 'PELÍCULA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 2,
                'nome' => 'SOFTPHONE',
                'descricao' => 'SOFTPHONE',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA COMPUTADOR/NOTEBOOK
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 3,
                'nome' => 'BATERIA',
                'descricao' => 'BATERIA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 3,
                'nome' => 'CARREGADOR',
                'descricao' => 'CARREGADOR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 3,
                'nome' => 'FÍSICO / HARDWARE',
                'descricao' => 'FÍSICO / HARDWARE',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 3,
                'nome' => 'INSTALAÇÃO',
                'descricao' => 'INSTALAÇÃO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 3,
                'nome' => 'PROGRAMAS',
                'descricao' => 'PROGRAMAS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 3,
                'nome' => 'SISTEMA OPERACIONAL',
                'descricao' => 'SISTEMA OPERACIONAL',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA IMPRESSORA
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 4,
                'nome' => 'IMPRESSÃO',
                'descricao' => 'IMPRESSÃO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 4,
                'nome' => 'PAPEL',
                'descricao' => 'PAPEL',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 4,
                'nome' => 'SCANNER',
                'descricao' => 'SCANNER',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 4,
                'nome' => 'TONER',
                'descricao' => 'TONER',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA PERIFÉRICOS
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 5,
                'nome' => 'CABOS',
                'descricao' => 'CABOS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 5,
                'nome' => 'MONITOR',
                'descricao' => 'MONITOR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 5,
                'nome' => 'MOUSE',
                'descricao' => 'MOUSE',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 5,
                'nome' => 'TECLADO',
                'descricao' => 'TECLADO',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA REDE / INTERNET
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 6,
                'nome' => 'CABO DE REDE',
                'descricao' => 'CABO DE REDE',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 6,
                'nome' => 'LINK DE DADOS',
                'descricao' => 'LINK DE DADOS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 6,
                'nome' => 'LINK DE VOZ',
                'descricao' => 'LINK DE VOZ',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 6,
                'nome' => 'PONTO DE REDE',
                'descricao' => 'PONTO DE REDE',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 6,
                'nome' => 'REDE',
                'descricao' => 'REDE',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 6,
                'nome' => 'WIRELESS',
                'descricao' => 'WIRELESS',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA SERVIDOR
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 7,
                'nome' => 'CONFIGURAÇÕES',
                'descricao' => 'CONFIGURAÇÕES',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 7,
                'nome' => 'DATA CENTER',
                'descricao' => 'DATA CENTER',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 7,
                'nome' => 'FALHAS',
                'descricao' => 'FALHAS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 7,
                'nome' => 'MÁQUINA VIRTUAL',
                'descricao' => 'MÁQUINA VIRTUAL',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA TELA DE MONITORAMENTO
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 8,
                'nome' => 'ATUALIZAR TELA',
                'descricao' => 'ATUALIZAR TELA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 8,
                'nome' => 'RASPBERRY (MINI PC)',
                'descricao' => 'RASPBERRY (MINI PC)',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 8,
                'nome' => 'TELEVISÃO',
                'descricao' => 'TELEVISÃO',
                'dicas' => '',
            ],


            // =========================================
            // CATEGORIA TELEFONIA
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 9,
                'nome' => 'HEADSET',
                'descricao' => 'HEADSET',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 9,
                'nome' => 'LIGAÇÕES',
                'descricao' => 'LIGAÇÕES',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 9,
                'nome' => 'NXT3000',
                'descricao' => 'NXT3000',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 9,
                'nome' => 'NXT-DISCADOR',
                'descricao' => 'NXT-DISCADOR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 9,
                'nome' => 'NXT-URATIVA',
                'descricao' => 'NXT-URATIVA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 9,
                'nome' => 'PAINEL DO USUÁRIO',
                'descricao' => 'PAINEL DO USUÁRIO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 9,
                'nome' => 'SOFTPHONE',
                'descricao' => 'SOFTPHONE',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => 1,
                'departamento_id' => 2,
                'ticket_categoria_id' => 9,
                'nome' => 'TELEFONE IP',
                'descricao' => 'TELEFONE IP',
                'dicas' => '',
            ],


        ]);


    }
}