<?php

use Illuminate\Database\Seeder;

class DepartamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departamentos')->insert([

            // ====================================================
            // ÁREA ADMINISTRATIVO-FINANCEIRO
            [
                'area_id' => 1,
                'nome' => 'ADMINISTRATIVO-FINANCEIRO',
                'descricao' => 'ADMINISTRATIVO-FINANCEIRO',
                'funcionario_id' => 40,
                'ticket' => false
            ],


            // ====================================================
            // ÁREA COMERCIAL
            [
                'area_id' => 2,
                'nome' => 'COMERCIAL',
                'descricao' => 'COMERCIAL',
                'funcionario_id' => 39,
                'ticket' => false
            ],
            [
                'area_id' => 2,
            	'nome' => 'APOIO COMERCIAL',
                'descricao' => 'APOIO COMERCIAL',
                'funcionario_id' => 8,
                'ticket' => false
            ],
            [
            	'area_id' => 2,
                'nome' => 'VENDAS',
                'descricao' => 'VENDAS',
                'funcionario_id' => 5,
                'ticket' => false
            ],


            // ====================================================
            // ÁREA ASSISTÊNCIA TÉCNICA
            [   
                'area_id' => 3,
                'nome' => 'ASSISTÊNCIA TÉCNICA',
                'descricao' => 'ASSISTÊNCIA TÉCNICA',
                'funcionario_id' => 26,
                'ticket' => false
            ],
            [	
            	'area_id' => 3,
                'nome' => 'ASSISTÊNCIA TÉCNICA EXTERNA',
                'descricao' => 'ASSISTÊNCIA TÉCNICA EXTERNA',
                'funcionario_id' => 26,
                'ticket' => false
            ],
            [   
                'area_id' => 3,
                'nome' => 'ASSISTÊNCIA TÉCNICA INTERNA',
                'descricao' => 'ASSISTÊNCIA TÉCNICA INTERNA',
                'funcionario_id' => 33,
                'ticket' => false
            ],


            // ====================================================
            // ÁREA TECNOLOGIA
            [   
                'area_id' => 4,
                'nome' => 'TECNOLOGIA',
                'descricao' => 'TECNOLOGIA',
                'funcionario_id' => 23,
                'ticket' => false
            ],
            [   
                'area_id' => 4,
                'nome' => 'DESENVOLVIMENTO DE SISTEMAS',
                'descricao' => 'DESENVOLVIMENTO DE SISTEMAS',
                'funcionario_id' => 25,
                'ticket' => false
            ],
            [   
                'area_id' => 4,
                'nome' => 'INFRAESTRUTURA DE TI',
                'descricao' => 'INFRAESTRUTURA DE TI',
                'funcionario_id' => 23,
                'ticket' => true
            ],
            [   
                'area_id' => 4,
                'nome' => 'SUPORTE AO PRODUTO',
                'descricao' => 'SUPORTE AO PRODUTO',
                'funcionario_id' => 32,
                'ticket' => false
            ],


            // ====================================================
            // ÁREA OPERAÇÕES
            [   
                'area_id' => 5,
                'nome' => 'OPERAÇÕES',
                'descricao' => 'OPERAÇÕES',
                'funcionario_id' => 17,
                'ticket' => false
            ],
            [   
                'area_id' => 5,
                'nome' => 'GESTÃO DE PESSOAS E DO CONHECIMENTO',
                'descricao' => 'GESTÃO DE PESSOAS E DO CONHECIMENTO',
                'funcionario_id' => 7,
                'ticket' => false
            ],
            [   
                'area_id' => 5,
                'nome' => 'GESTÃO DO CONHECIMENTO',
                'descricao' => 'GESTÃO DO CONHECIMENTO',
                'funcionario_id' => 7,
                'ticket' => false
            ],
            [   
                'area_id' => 5,
                'nome' => 'MARKETING',
                'descricao' => 'MARKETING',
                'funcionario_id' => 7,
                'ticket' => false
            ],
            [   
                'area_id' => 5,
                'nome' => 'PROJETOS',
                'descricao' => 'PROJETOS',
                'funcionario_id' => 17,
                'ticket' => false
            ],
            [   
                'area_id' => 5,
                'nome' => 'SUPORTE AO CLIENTE',
                'descricao' => 'SUPORTE AO CLIENTE',
                'funcionario_id' => 13,
                'ticket' => false
            ],
            [   
                'area_id' => 5,
                'nome' => 'TREINAMENTO DE PRODUTO',
                'descricao' => 'TREINAMENTO DE PRODUTO',
                'funcionario_id' => 17,
                'ticket' => false
            ],
        ]);
    }
}
