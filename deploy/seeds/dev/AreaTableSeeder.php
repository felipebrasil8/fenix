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
                'nome' => 'TECNOLOGIA',
                'funcionario_id' => 1,
                'descricao' => 'ÁREA DE TECNOLOGIA.'
            ],
            [
                'nome' => 'COMERCIAL',
                'funcionario_id' => 1,
                'descricao' => 'ÁREA COMERCIAL.'
            ],
            [
                'nome' => 'OPERAÇÕES',
                'funcionario_id' => 1,
                'descricao' => 'ÁREA DE OPERAÇÕES.'
            ],
            [
                'nome' => 'ADMINISTRATIVO/FINANCEIRO',
                'funcionario_id' => 1,
                'descricao' => 'ÁREA ADMINISTRATIVA E FINANCEIRA.'
            ],
            [
                'nome' => 'ASSISTÊNCIA TÉCNICA',
                'funcionario_id' => 1,
                'descricao' => 'ÁREA DE ASSISTÊNCIA TÉCNICA.'
            ]
        ]);
    }
}