<?php

use Illuminate\Database\Seeder;

class TicketStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tickets_status')->insert([
            [
                'nome' => 'NOVO',
                'ordem' => 10,
                'aberto' => 'TRUE',
                'descricao' => 'TICKET CRIADO E NÃO CATEGORIZADO',
                'departamento_id' => 2,
                'cor' => '#dd4b39'
            ],
            [
                'nome' => 'ABERTO',
                'ordem' => 20,
                'aberto' => 'TRUE',
                'descricao' => 'TICKET CATEGORIZADO',
                'departamento_id' => 2,
                'cor' => '#f39c12'
            ],
            [
                'nome' => 'AGUARDANDO',
                'ordem' => 30,
                'aberto' => 'TRUE',
                'descricao' => 'TICKET AGUARDANDO INFORMAÇÕES',
                'departamento_id' => 2,
                'cor' => '#00c0ef'
            ],
            [
                'nome' => 'RESOLVIDO',
                'ordem' => 40,
                'aberto' => 'TRUE',
                'descricao' => 'TICKET RESOLVIDO',
                'departamento_id' => 2,
                'cor' => '#00a65a'
            ],
            [
                'nome' => 'FECHADO',
                'ordem' => 50,
                'aberto' => 'FALSE',
                'descricao' => 'TICKET FECHADO',
                'departamento_id' => 2,
                'cor' => '#c1c1c1'
            ],
            [
                'nome' => 'ERRO',
                'ordem' => 60,
                'aberto' => 'FALSE',
                'descricao' => 'TICKET ERRO',
                'departamento_id' => 2,
                'cor' => '#353535'
            ]
        ]);
    }
}