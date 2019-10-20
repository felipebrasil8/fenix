<?php

use Illuminate\Database\Seeder;

class ConfiguracoesTicketsSprint015 extends Seeder
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

        // Configuração de gestao do conhecimento
        $this->gestaoDoConhecimento($usuario_id);

        // Configuração de administrativo/financeiro
        $this->administrativoFinanceiro($usuario_id);

        // Configuração de desenvolvimento de sistemas
        $this->desenvolvimento($usuario_id);

    }

    private function gestaoDoConhecimento($usuario_id){

        // Busca ID do departamento, caso nao exista, cria um novo
        $departamento = DB::table('departamentos')->where('nome', 'GESTÃO DO CONHECIMENTO')->get();

        if ($departamento->isEmpty()){

            DB::table('departamentos')->insert([
                'area_id' => 1,
                'nome' => 'GESTÃO DO CONHECIMENTO',
                'descricao' => 'GESTÃO DO CONHECIMENTO',
                'funcionario_id' => 1,
                'ticket' => true
            ]);

            $departamento_id = DB::table('departamentos')->where('nome', 'GESTÃO DO CONHECIMENTO')->first()->id;

        } else {
            $departamento_id = $departamento->first()->id;
            DB::table('departamentos')->where('id', $departamento_id)->update(['ticket' => true]);
        }


        // Deleta categorias atuais do departamento
        DB::table('tickets_categoria')->where('departamento_id', '=', $departamento_id)->delete();


        // Cria as categorias pai
        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'PRODUTOS',
                'descricao' => 'PRODUTOS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'PROCESSOS',
                'descricao' => 'PROCESSOS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'CONHECIMENTOS GERAIS',
                'descricao' => 'CONHECIMENTOS GERAIS',
                'dicas' => '',
            ],

        ]);


        // Cria as categorias filhas de PRODUTOS
        $categoria_pai_id = DB::table('tickets_categoria')->where([
        	['departamento_id', '=', $departamento_id],
        	['nome', '=', 'PRODUTOS'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NXT3000',
                'descricao' => 'NXT3000',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NXT-DISCADOR',
                'descricao' => 'NXT-DISCADOR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NXT-URATIVA',
                'descricao' => 'NXT-URATIVA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NXT-IPBX',
                'descricao' => 'NXT-IPBX',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'VOZ',
                'descricao' => 'VOZ',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'FENIX',
                'descricao' => 'FENIX',
                'dicas' => '',
            ],

        ]);


		// Cria as categorias filhas de PROCESSOS
        $categoria_pai_id = DB::table('tickets_categoria')->where([
        	['departamento_id', '=', $departamento_id],
        	['nome', '=', 'PROCESSOS'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NEGÓCIO',
                'descricao' => 'NEGÓCIO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'APOIO',
                'descricao' => 'APOIO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'TAREFAS',
                'descricao' => 'TAREFAS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'CHECK-LIST',
                'descricao' => 'CHECK-LIST',
                'dicas' => '',
            ],

        ]);


		// Cria as categorias filhas de CONHECIMENTOS GERAIS
        $categoria_pai_id = DB::table('tickets_categoria')->where([
        	['departamento_id', '=', $departamento_id],
        	['nome', '=', 'CONHECIMENTOS GERAIS'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'CONHECIMENTOS GERAIS',
                'descricao' => 'CONHECIMENTOS GERAIS',
                'dicas' => '',
            ],

        ]);


        // Deleta campos adicionais atuais do departamento
        DB::table('tickets_campo')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os campos adicionais de GESTAO DO CONHECIMENTO
        DB::table('tickets_campo')->insert([

        	[
        		'usuario_inclusao_id' => $usuario_id,
        		'departamento_id' => $departamento_id,
        		'nome' => 'TIPO DE SUGESTÃO',
        		'descricao' => 'TIPO DE SUGESTÃO',
        		'padrao' => '',
        		'visivel' => false,
        		'obrigatorio' => true,
        		'tipo' => 'LISTA',
        		'dados' => '['.
        			'{"id":1,"padrao":"false","valor":"ATUALIZAÇÃO"},'.
        			'{"id":2,"padrao":"false","valor":"NOVO"},'.
        			'{"id":3,"padrao":"false","valor":"REMOÇÃO"}'.
        		']',
        	],

        	[
        		'usuario_inclusao_id' => $usuario_id,
        		'departamento_id' => $departamento_id,
        		'nome' => 'MENU',
        		'descricao' => 'MENU',
        		'padrao' => '',
        		'visivel' => false,
        		'obrigatorio' => true,
        		'tipo' => 'LISTA',
        		'dados' => '['.
        			'{"id":1,"padrao":"false","valor":"BASE"},'.
        			'{"id":2,"padrao":"false","valor":"COMUNICAÇÃO INTERNA"},'.
        			'{"id":3,"padrao":"false","valor":"FAQ"},'.
        			'{"id":4,"padrao":"false","valor":"FÓRUM"},'.
        			'{"id":5,"padrao":"false","valor":"LIÇÕES"},'.
        			'{"id":6,"padrao":"false","valor":"LINKS"},'.
        			'{"id":7,"padrao":"false","valor":"PROCESSOS"}'.
        		']',
        	],

        	[
        		'usuario_inclusao_id' => $usuario_id,
        		'departamento_id' => $departamento_id,
                'nome' => 'TÍTULO MATÉRIA',
                'descricao' => 'TÍTULO MATÉRIA',
                'padrao' => '',
                'visivel' => false,
                'obrigatorio' => true,
                'tipo' => 'TEXTO',
                'dados' => 'null',
        	],

        	[
        		'usuario_inclusao_id' => $usuario_id,
        		'departamento_id' => $departamento_id,
        		'nome' => 'ORIGEM',
        		'descricao' => 'ORIGEM',
        		'padrao' => '',
        		'visivel' => false,
        		'obrigatorio' => true,
        		'tipo' => 'LISTA',
        		'dados' => '['.
        			'{"id":1,"padrao":"false","valor":"COMENTÁRIOS"},'.
        			'{"id":2,"padrao":"false","valor":"E-MAIL"},'.
        			'{"id":3,"padrao":"false","valor":"INTERNO"},'.
        			'{"id":4,"padrao":"false","valor":"PESSOALMENTE"},'.
        			'{"id":5,"padrao":"false","valor":"TELEFONE"},'.
        			'{"id":6,"padrao":"false","valor":"WEB"}'.
        		']',
        	],

        	[
        		'usuario_inclusao_id' => $usuario_id,
        		'departamento_id' => $departamento_id,
                'nome' => 'COLABORADORES',
                'descricao' => 'COLABORADORES',
                'padrao' => '',
                'visivel' => false,
                'obrigatorio' => false,
                'tipo' => 'TEXTO',
                'dados' => 'null',
        	],

        	[
        		'usuario_inclusao_id' => $usuario_id,
        		'departamento_id' => $departamento_id,
                'nome' => 'DATA DA PUBLICAÇÃO',
                'descricao' => 'DATA DA PUBLICAÇÃO',
                'padrao' => '',
                'visivel' => false,
                'obrigatorio' => false,
                'tipo' => 'TEXTO',
                'dados' => 'null',
        	],

        ]);


        // Deleta campos status atuais do departamento
        DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os status de GESTAO DO CONHECIMENTO
        DB::table('tickets_status')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'PENDENTE',
                'descricao' => 'TICKET PENDENTE',
                'ordem' => 10,
                'aberto' => true,
                'cor' => '#dd4b39',
            ],
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'EM ANÁLISE',
                'descricao' => 'TICKET EM ANÁLISE',
                'ordem' => 20,
                'aberto' => true,
                'cor' => '#f39c12',
            ],
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'VALIDAÇÃO',
                'descricao' => 'TICKET EM VALIDAÇÃO',
                'ordem' => 30,
                'aberto' => true,
                'cor' => '#00c0ef',
            ],
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'PUBLICAR',
                'descricao' => 'TICKET EM PUBLICAÇÃO',
                'ordem' => 40,
                'aberto' => true,
                'cor' => '#00a65a',
            ],
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'PUBLICADO',
                'descricao' => 'TICKET PUBLICADO',
                'ordem' => 50,
                'aberto' => false,
                'cor' => '#c1c1c1',
            ],
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'REJEITADO',
                'descricao' => 'TICKET REJEITADO',
                'ordem' => 60,
                'aberto' => false,
                'cor' => '#353535',
            ],

        ]);


        // Deleta campos prioridade atuais do departamento
        DB::table('tickets_prioridade')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os prioridade de GESTAO DO CONHECIMENTO
        DB::table('tickets_prioridade')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'BAIXA',
                'prioridade' => 10,
                'cor' => '#00a65a',
            ],
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'NORMAL',
                'prioridade' => 20,
                'cor' => '#00c0ef',
            ],
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'ALTA',
                'prioridade' => 30,
                'cor' => '#dd4b39',
            ],

        ]);

    }


    private function administrativoFinanceiro($usuario_id){

        // Busca ID do departamento, caso nao exista, cria um novo
        $departamento = DB::table('departamentos')->where('nome', 'ADMINISTRATIVO-FINANCEIRO')->get();

        if ($departamento->isEmpty()){

            DB::table('departamentos')->insert([
                'area_id' => 1,
                'nome' => 'ADMINISTRATIVO E FINANCEIRO',
                'descricao' => 'ADMINISTRATIVO E FINANCEIRO',
                'funcionario_id' => 1,
                'ticket' => true
            ]);

            $departamento_id = DB::table('departamentos')->where('nome', 'ADMINISTRATIVO E FINANCEIRO')->first()->id;

        } else {
            $departamento_id = $departamento->first()->id;
            DB::table('departamentos')->where('id', $departamento_id)->update(['ticket' => true,'nome' => 'ADMINISTRATIVO E FINANCEIRO']);
        }


        // Deleta categorias atuais do departamento
        DB::table('tickets_categoria')->where('departamento_id', '=', $departamento_id)->delete();


        // Cria as categorias pai
        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'MANUTENÇÕES',
                'descricao' => 'MANUTENÇÕES',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'FINANCEIRO',
                'descricao' => 'FINANCEIRO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'ESCRITÓRIO',
                'descricao' => 'ESCRITÓRIO',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'TAREFAS',
                'descricao' => 'TAREFAS',
                'dicas' => '',
            ],

        ]);


        // Cria as categorias filhas de MANUTENÇÕES
        $categoria_pai_id = DB::table('tickets_categoria')->where([
            ['departamento_id', '=', $departamento_id],
            ['nome', '=', 'MANUTENÇÕES'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'ELÉTRICO',
                'descricao' => 'ELÉTRICO',
                'dicas' => 'EX: TOMADAS, LÂMPADAS, ETC...',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'HIDRÁULICO',
                'descricao' => 'HIDRÁULICO',
                'dicas' => 'EX: RALO, TORNEIRA, VAZAMENTOS, ENTUPIMENTOS, ETC...',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'MARCENEIRO',
                'descricao' => 'MARCENEIRO',
                'dicas' => 'EX: MADEIRA, MESA, ARMÁRIOS, ETC...',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'ELETRODOMÉSTICOS',
                'descricao' => 'ELETRODOMÉSTICOS',
                'dicas' => 'EX: FILTRO DE ÁGUA, GELADEIRA, MICRO-ONDAS, CAFETEIRA, ETC...',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'PREVENTIVAS',
                'descricao' => 'PREVENTIVAS',
                'dicas' => 'EX: DEDETIZAÇÃO, AR-CONDICIONADO, ETC...',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'AR-CONDICIONADO',
                'descricao' => 'AR-CONDICIONADO',
                'dicas' => 'PROBLEMAS NÃO PREVENTIVOS',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'ESTRUTURA',
                'descricao' => 'ESTRUTURA',
                'dicas' => 'EX: PORTA, PISO, PINTURA, PAREDE, CADEIRAS, VIDROS, CHAVEIRO, ETC...',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'ORGANIZAÇÃO',
                'descricao' => 'ORGANIZAÇÃO',
                'dicas' => 'DIPOSIÇÃO FÍSICA DE OBJETOS E LIMPEZA',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'OUTROS',
                'descricao' => 'OUTROS',
                'dicas' => '',
            ],

        ]);


        // Cria as categorias filhas de MANUTENÇÕES
        $categoria_pai_id = DB::table('tickets_categoria')->where([
            ['departamento_id', '=', $departamento_id],
            ['nome', '=', 'FINANCEIRO'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'EMISSÃO DE NOTAS',
                'descricao' => 'EMISSÃO DE NOTAS',
                'dicas' => 'TROCA, DEVOLUÇÃO, CONSERTO, EQUIPAMENTOS PARA CLIENTE, ETC...',
            ],

        ]);


        // Cria as categorias filhas de MANUTENÇÕES
        $categoria_pai_id = DB::table('tickets_categoria')->where([
            ['departamento_id', '=', $departamento_id],
            ['nome', '=', 'ESCRITÓRIO'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'MATERIAIS DE ESCRITÓRIO',
                'descricao' => 'MATERIAIS DE ESCRITÓRIO',
                'dicas' => 'EX: LÁPIS, CANETAS, PAPEL, PASTAS, FITAS, ETC... (IMPRESSORAS É INFRAESTRUTURA DE TI)',
            ],
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'OUTROS MATERIAIS',
                'descricao' => 'OUTROS MATERIAIS',
                'dicas' => '',
            ],

        ]);


        // Cria as categorias filhas de MANUTENÇÕES
        $categoria_pai_id = DB::table('tickets_categoria')->where([
            ['departamento_id', '=', $departamento_id],
            ['nome', '=', 'TAREFAS'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'SOLICITAÇÕES DO RH',
                'descricao' => 'SOLICITAÇÕES DO RH',
                'dicas' => '',
            ],

        ]);


        // Deleta campos status atuais do departamento
        DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os status de GESTAO DO CONHECIMENTO
        DB::table('tickets_status')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'NOVO',
                'descricao' => 'TICKET NOVO',
                'ordem' => 10,
                'aberto' => true,
                'cor' => '#dd4b39',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'ABERTO',
                'descricao' => 'TICKET ABERTO',
                'ordem' => 20,
                'aberto' => true,
                'cor' => '#f39c12',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'AGUARDANDO',
                'descricao' => 'TICKET AGUARDANDO INFORMAÇÕES',
                'ordem' => 30,
                'aberto' => true,
                'cor' => '#00c0ef',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'AGENDADO',
                'descricao' => 'TICKET AGENDADO PARA OUTRA DATA',
                'ordem' => 40,
                'aberto' => true,
                'cor' => '#00a65a',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'FECHADO',
                'descricao' => 'TICKET FECHADO',
                'ordem' => 50,
                'aberto' => false,
                'cor' => '#c1c1c1',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'ERRO',
                'descricao' => 'TICKET CRIADO DE FORMA ERRADA',
                'ordem' => 60,
                'aberto' => false,
                'cor' => '#353535',
            ],

        ]);


        // Deleta campos prioridade atuais do departamento
        DB::table('tickets_prioridade')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os prioridade de GESTAO DO CONHECIMENTO
        DB::table('tickets_prioridade')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'BAIXA',
                'prioridade' => 10,
                'cor' => '#00a65a',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'PROGRAMADA',
                'prioridade' => 20,
                'cor' => '#353535',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'NORMAL',
                'prioridade' => 30,
                'cor' => '#00c0ef',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'ALTA',
                'prioridade' => 40,
                'cor' => '#f39c12',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'URGENTE',
                'prioridade' => 50,
                'cor' => '#dd4b39',
            ],

        ]);


        // Deleta campos adicionais atuais do departamento
        DB::table('tickets_campo')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os campos adicionais de GESTAO DO CONHECIMENTO
        DB::table('tickets_campo')->insert([

            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'TIPO',
                'descricao' => 'TIPO',
                'padrao' => '',
                'visivel' => true,
                'obrigatorio' => false,
                'tipo' => 'LISTA',
                'dados' => '['.
                    '{"id":1,"padrao":"false","valor":"OCORRÊNCIA"},'.
                    '{"id":2,"padrao":"false","valor":"PREVENTIVA"},'.
                    '{"id":3,"padrao":"false","valor":"SOLICITAÇÃO"}'.
                ']',
            ],

        ]);

    }


    private function desenvolvimento($usuario_id){

        // Busca ID do departamento, caso nao exista, cria um novo
        $departamento = DB::table('departamentos')->where('nome', 'DESENVOLVIMENTO DE SISTEMAS')->get();

        if ($departamento->isEmpty()){

            DB::table('departamentos')->insert([
                'area_id' => 1,
                'nome' => 'DESENVOLVIMENTO DE SISTEMAS',
                'descricao' => 'DESENVOLVIMENTO DE SISTEMAS',
                'funcionario_id' => 1,
                'ticket' => true
            ]);

            $departamento_id = DB::table('departamentos')->where('nome', 'DESENVOLVIMENTO DE SISTEMAS')->first()->id;

        } else {
            $departamento_id = $departamento->first()->id;
            DB::table('departamentos')->where('id', $departamento_id)->update(['ticket' => true]);
        }


        // Deleta categorias atuais do departamento
        DB::table('tickets_categoria')->where('departamento_id', '=', $departamento_id)->delete();


        // Cria as categorias pai
        DB::table('tickets_categoria')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'PRODUTOS',
                'descricao' => 'SUGESTÕES RELACIONADAS AOS PRODUTOS',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => NULL,
                'nome' => 'SISTEMAS INTERNOS',
                'descricao' => 'SUGESTÕES RELACIONADAS AOS SISTEMAS INTERNOS',
                'dicas' => '',
            ],

        ]);


        // Cria as categorias filhas de MANUTENÇÕES
        $categoria_pai_id = DB::table('tickets_categoria')->where([
            ['departamento_id', '=', $departamento_id],
            ['nome', '=', 'PRODUTOS'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NXT3000',
                'descricao' => 'NXT3000',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NXT-DISCADOR',
                'descricao' => 'NXT-DISCADOR',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NXT-URATIVA',
                'descricao' => 'NXT-URATIVA',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'NXT-IPBX',
                'descricao' => 'NXT-IPBX',
                'dicas' => '',
            ],
        ]);


        // Cria as categorias filhas de MANUTENÇÕES
        $categoria_pai_id = DB::table('tickets_categoria')->where([
            ['departamento_id', '=', $departamento_id],
            ['nome', '=', 'SISTEMAS INTERNOS'],
        ])->first()->id;

        DB::table('tickets_categoria')->insert([
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'CRM',
                'descricao' => 'CRM',
                'dicas' => '',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'ticket_categoria_id' => $categoria_pai_id,
                'nome' => 'FENIX',
                'descricao' => 'FENIX',
                'dicas' => '',
            ],
        ]);


        // Deleta campos status atuais do departamento
        DB::table('tickets_status')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os status de GESTAO DO CONHECIMENTO
        DB::table('tickets_status')->insert([
            
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'NOVO',
                'descricao' => 'TICKET NOVO',
                'ordem' => 10,
                'aberto' => true,
                'cor' => '#dd4b39',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'EM ANÁLISE',
                'descricao' => 'TICKET EM ANÁLISE',
                'ordem' => 20,
                'aberto' => true,
                'cor' => '#f39c12',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'BACKLOG',
                'descricao' => 'TICKET EM BACKLOG',
                'ordem' => 30,
                'aberto' => true,
                'cor' => '#00c0ef',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'DESENVOLVIMENTO',
                'descricao' => 'TICKET EM DESENVOLVIMENTO',
                'ordem' => 40,
                'aberto' => true,
                'cor' => '#00a65a',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'CONCLUÍDO',
                'descricao' => 'TICKET CONCLUÍDO',
                'ordem' => 50,
                'aberto' => false,
                'cor' => '#c1c1c1',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'RECUSADO',
                'descricao' => 'TICKET RECUSADO',
                'ordem' => 60,
                'aberto' => false,
                'cor' => '#353535',
            ],

        ]);


        // Deleta campos prioridade atuais do departamento
        DB::table('tickets_prioridade')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os prioridade de GESTAO DO CONHECIMENTO
        DB::table('tickets_prioridade')->insert([
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'BAIXA',
                'prioridade' => 10,
                'cor' => '#00a65a',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'ALTA',
                'prioridade' => 40,
                'cor' => '#dd4b39',
            ],
        ]);


        // Deleta campos adicionais atuais do departamento
        DB::table('tickets_campo')->where('departamento_id', '=', $departamento_id)->delete();


        // Criar os campos adicionais de GESTAO DO CONHECIMENTO
        DB::table('tickets_campo')->insert([
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'ORIGEM',
                'descricao' => 'ORIGEM DA SUGESTÃO',
                'padrao' => '',
                'visivel' => true,
                'obrigatorio' => true,
                'tipo' => 'LISTA',
                'dados' => '['.
                    '{"id":1,"padrao":"false","valor":"CORREÇÃO DE BUG"},'.
                    '{"id":2,"padrao":"false","valor":"SUGESTÃO DE CLIENTE"},'.
                    '{"id":3,"padrao":"false","valor":"SUGESTÃO DE COLABORADOR"}'.
                ']',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'CLIENTE',
                'descricao' => 'NOME DO CLIENTE',
                'padrao' => '',
                'visivel' => true,
                'obrigatorio' => false,
                'tipo' => 'TEXTO',
                'dados' => 'null',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'CHAMADO',
                'descricao' => 'NÚMERO DO CHAMADO',
                'padrao' => '',
                'visivel' => true,
                'obrigatorio' => false,
                'tipo' => 'TEXTO',
                'dados' => 'null',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'JOB',
                'descricao' => 'NÚMERO DO JOB',
                'padrao' => '',
                'visivel' => true,
                'obrigatorio' => false,
                'tipo' => 'TEXTO',
                'dados' => 'null',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'SPRINT',
                'descricao' => 'SPRINT QUE FOI DESENVOLVIDO',
                'padrao' => '',
                'visivel' => true,
                'obrigatorio' => false,
                'tipo' => 'TEXTO',
                'dados' => 'null',
            ],
            [
                'usuario_inclusao_id' => $usuario_id,
                'departamento_id' => $departamento_id,
                'nome' => 'VERSÃO',
                'descricao' => 'VERSÃO QUE FOI IMPLANTADO',
                'padrao' => '',
                'visivel' => true,
                'obrigatorio' => false,
                'tipo' => 'TEXTO',
                'dados' => 'null',
            ],
        ]);



    }

}
