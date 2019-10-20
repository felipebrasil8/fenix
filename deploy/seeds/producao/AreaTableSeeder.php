<?php

use Illuminate\Database\Seeder;

class AreaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->insert([
            [
                'nome' => 'ADMINISTRATIVO-FINANCEIRO',
                'funcionario_id' => 40,
                'descricao' => 'ÁREA ADMINISTRATIVA E FINANCEIRA.'
            ],
            [
                'nome' => 'COMERCIAL',
                'funcionario_id' => 39,
                'descricao' => 'ÁREA COMERCIAL.'
            ],
            [
                'nome' => 'ASSISTÊNCIA TÉCNICA',
                'funcionario_id' => 26,
                'descricao' => 'ÁREA DE ASSISTÊNCIA TÉCNICA.'
            ],
            [
                'nome' => 'TECNOLOGIA',
                'funcionario_id' => 23,
                'descricao' => 'ÁREA DE TECNOLOGIA.'
            ],
            [
                'nome' => 'OPERAÇÕES',
                'funcionario_id' => 17,
                'descricao' => 'ÁREA DE OPERAÇÕES.'
            ]
        ]);
    }
}