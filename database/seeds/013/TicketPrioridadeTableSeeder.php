<?php

use Illuminate\Database\Seeder;

class TicketPrioridadeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tickets_prioridade')->insert([
            [
                'nome' => 'BAIXA',
                'prioridade' => 0,
                'cor' => '#00a65a',
                'departamento_id' => 2
            ],
            [
                'nome' => 'NORMAL',
                'prioridade' => 10,
                'cor' => '#00c0ef',
                'departamento_id' => 2
            ],
            [
                'nome' => 'ALTA',
                'prioridade' => 20,
                'cor' => '#f39c12',
                'departamento_id' => 2
            ],
            [
                'nome' => 'URGENTE',
                'prioridade' => 30,
                'cor' => '#dd4b39',
                'departamento_id' => 2
            ]
        ]);
    }
}
