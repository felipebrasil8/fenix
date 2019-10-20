<?php

use Illuminate\Database\Seeder;

class CargoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cargos')->insert([

            // ====================================================
            // DEPARTAMENTO ADMINISTRATIVO-FINANCEIRO
            [
                'departamento_id' => 1,
                'nome' => 'DIRETOR ADMINISTRATIVO-FINANCEIRO',
                'descricao' => 'DIRETOR ADMINISTRATIVO-FINANCEIRO',
                'funcionario_id' => 40
            ],
            [
                'departamento_id' => 1,
                'nome' => 'ANALISTA ADMINISTRATIVO-FINANCEIRO',
                'descricao' => 'ANALISTA ADMINISTRATIVO-FINANCEIRO',
                'funcionario_id' => 40,
            ],
            [
                'departamento_id' => 1,
                'nome' => 'RECEPCIONISTA',
                'descricao' => 'RECEPCIONISTA',
                'funcionario_id' => 40,
            ],
            [
                'departamento_id' => 1,
                'nome' => 'COPEIRA',
                'descricao' => 'COPEIRA',
                'funcionario_id' => 40,
            ],


            // ====================================================
            // DEPARTAMENTO COMERCIAL
            [
                'departamento_id' => 2,
                'nome' => 'DIRETOR COMERCIAL',
                'descricao' => 'DIRETOR COMERCIAL',
                'funcionario_id' => 39,
            ],
            [
                'departamento_id' => 3,
                'nome' => 'COORDENADOR COMERCIAL',
                'descricao' => 'COORDENADOR COMERCIAL',
                'funcionario_id' => 39,
            ],
            [
                'departamento_id' => 3,
                'nome' => 'ANALISTA COMERCIAL',
                'descricao' => 'ANALISTA COMERCIAL',
                'funcionario_id' => 8,
            ],
            [
                'departamento_id' => 3,
                'nome' => 'CONSULTOR DE VENDAS INTERNO',
                'descricao' => 'CONSULTOR DE VENDAS INTERNAS',
                'funcionario_id' => 8,
            ],
            [
                'departamento_id' => 4,
                'nome' => 'SUPERVISOR DE VENDAS',
                'descricao' => 'SUPERVISOR DE VENDAS',
                'funcionario_id' => 39,
            ],
            [
                'departamento_id' => 4,
                'nome' => 'CONSULTOR DE VENDAS EXTERNO',
                'descricao' => 'CONSULTOR DE VENDAS EXTERNAS',
                'funcionario_id' => 5,
            ],


            // ====================================================
            // DEPARTAMENTO ASSISTÊNCIA TÉCNICA
            [
                'departamento_id' => 5,
                'nome' => 'DIRETOR DE ASSISTÊNCIA TÉCNICA',
                'descricao' => 'DIRETOR DE ASSISTÊNCIA TÉCNICA',
                'funcionario_id' => 26,
            ],
            [
                'departamento_id' => 6,
                'nome' => 'ASSISTÊNCIA TÉCNICA EXTERNA',
                'descricao' => 'ASSISTÊNCIA TÉCNICA EXTERNA',
                'funcionario_id' => 26,
            ],
            [
                'departamento_id' => 7,
                'nome' => 'SUPERVISOR DE ASSISTÊNCIA TÉCNICA',
                'descricao' => 'SUPERVISOR DE ASSISTÊNCIA TÉCNICA',
                'funcionario_id' => 26,
            ],
            [
                'departamento_id' => 7,
                'nome' => 'ASSISTÊNCIA TÉCNICA INTERNA',
                'descricao' => 'ASSISTÊNCIA TÉCNICA INTERNA',
                'funcionario_id' => 33,
            ],


            // ====================================================
            // DEPARTAMENTO TECNOLOGIA
            [
                'departamento_id' => 8,
                'nome' => 'DIRETOR DE TECNOLOGIA',
                'descricao' => 'DIRETOR TECNOLOGIA',
                'funcionario_id' => 23,
            ],
            [
                'departamento_id' => 9,
                'nome' => 'SUPERVISOR DE DESENVOLVIMENTO',
                'descricao' => 'SUPERVISOR DE DESENVOLVIMENTO',
                'funcionario_id' => 23,
            ],
            [
                'departamento_id' => 9,
                'nome' => 'DESENVOLVEVOR DE SISTEMAS',
                'descricao' => 'DESENVOLVEVOR DE SISTEMAS',
                'funcionario_id' => 25,
            ],
            [
                'departamento_id' => 10,
                'nome' => 'ANALISTA DE INTRAESTRUTURA DE TI',
                'descricao' => 'ANALISTA DE INTRAESTRUTURA DE TI',
                'funcionario_id' => 23,
            ],
            [
                'departamento_id' => 10,
                'nome' => 'AUXILIAR DE INFRAESTRUTURA DE TI',
                'descricao' => 'AUXILIAR DE INFRAESTRUTURA DE TI',
                'funcionario_id' => 21,
            ],
            [
                'departamento_id' => 11,
                'nome' => 'SUPERVISOR DE SUPORTE AO PRODUTO',
                'descricao' => 'SUPERVISOR DE SUPORTE AO PRODUTO',
                'funcionario_id' => 23,
            ],
            [
                'departamento_id' => 11,
                'nome' => 'ANALISTA DE SUPORTE AO PRODUTO',
                'descricao' => 'ANALISTA DE SUPORTE AO PRODUTO',
                'funcionario_id' => 32,
            ],


            // ====================================================
            // DEPARTAMENTO OPERAÇÕES
            [
                'departamento_id' => 12,
                'nome' => 'DIRETOR DE OPERAÇÕES',
                'descricao' => 'DIRETOR DE OPERAÇÕES',
                'funcionario_id' => 17,
            ],
            [
                'departamento_id' => 13,
                'nome' => 'GERENTE DE DESENVOLVIMENTO HUMANO E ORGANIZACIONAL',
                'descricao' => 'GERENTE DE DESENVOLVIMENTO HUMANO E ORGANIZACIONAL',
                'funcionario_id' => 17,
            ],
            [
                'departamento_id' => 14,
                'nome' => 'ANALISTA DE INFORMAÇÕES',
                'descricao' => 'ANALISTA DE INFORMAÇÕES',
                'funcionario_id' => 7,
            ],
            [
                'departamento_id' => 15,
                'nome' => 'ANALISTA DE MARKETING',
                'descricao' => 'ANALISTA DE MARKETING',
                'funcionario_id' => 7,
            ],
            [
                'departamento_id' => 16,
                'nome' => 'ANALISTA DE PROJETOS',
                'descricao' => 'ANALISTA DE PROJETOS',
                'funcionario_id' => 17,
            ],
            [
                'departamento_id' => 17,
                'nome' => 'SUPERVISOR DE SUPORTE AO CLIENTE',
                'descricao' => 'SUPERVISOR DE SUPORTE AO CLIENTE',
                'funcionario_id' => 17,
            ],
            [
                'departamento_id' => 17,
                'nome' => 'ANALISTA DE SUPORTE AO CLIENTE',
                'descricao' => 'ANALISTA DE SUPORTE AO CLIENTE',
                'funcionario_id' => 13,
            ],
            [
                'departamento_id' => 18,
                'nome' => 'ANALISTA DE TREINAMENTO',
                'descricao' => 'ANALISTA DE TREINAMENTO',
                'funcionario_id' => 17,
            ],
        ]);
    }
}
