<?php

use Illuminate\Database\Seeder;

class TicketCamposTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tickets_campo')->insert([
            [
                'nome' => 'ORIGEM',
                'tipo' => 'LISTA',
                'padrao' => '',
                'descricao' => 'ORIGEM DO TICKET',
                'visivel' => 'TRUE',
                'obrigatorio' => 'FALSE',
                'dados' => '[{"id":1,"padrao":"false","valor":"TELEFONE"},{"id":2,"padrao":"false","valor":"E-MAIL"},{"id":3,"padrao":"false","valor":"WEB"},{"id":4,"padrao":"false","valor":"MONITORAMENTO"}]',
                'departamento_id' => 2
            ],
            [
                'nome' => 'TIPO',
                'tipo' => 'LISTA',
                'padrao' => '',
                'descricao' => 'TIPO DE SOLICITAÇÃO',
                'visivel' => 'TRUE',
                'obrigatorio' => 'TRUE',
                'dados' => '[{"id":1,"padrao":"false","valor":"DÚVIDA"},{"id":2,"padrao":"false","valor":"PROBLEMA"},{"id":3,"padrao":"false","valor":"TAREFA"},{"id":4,"padrao":"false","valor":"INCIDENTE"}]',
                'departamento_id' => 2
            ],
            [
                'nome' => 'TRATATIVA',
                'tipo' => 'LISTA',
                'padrao' => '',
                'descricao' => 'TRATATIVA ATUAL DO TICKET',
                'visivel' => 'TRUE',
                'obrigatorio' => 'FALSE',
                'dados' => '[{"id":1,"padrao":"false","valor":"COMUM"},{"id":2,"padrao":"false","valor":"AGUARDANDO FORNECEDOR"},{"id":3,"padrao":"false","valor":"AGUARDANDO SUPORTE NOVAX"},{"id":4,"padrao":"false","valor":"COMPRAS"},{"id":5,"padrao":"false","valor":"MANUTENÇÃO"},{"id":6,"padrao":"false","valor":"USUÁRIO"}]',
                'departamento_id' => 2
            ]
        ]);
    }
}
