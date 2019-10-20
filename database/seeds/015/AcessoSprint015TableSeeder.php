<?php

use Illuminate\Database\Seeder;

class AcessoSprint015TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acessos')->insert([
            [
                'nome' => 'TICKETS - TRATAR/EDITAR TODOS OS TICKETS'
            ]
        ]);
    }
}
