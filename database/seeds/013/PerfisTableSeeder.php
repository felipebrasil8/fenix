<?php

use Illuminate\Database\Seeder;

class PerfisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('perfis')->insert([
            [
                'nome' => 'ADMINISTRADOR'
            ],
            [
                'nome' => 'INFRAESTRUTURA DE TI'
            ],
            [
                'nome' => 'GESTÃO DE PESSOAS'
            ],
            [
                'nome' => 'USUÁRIO COMUM'
            ]
        ]);
    }
}
