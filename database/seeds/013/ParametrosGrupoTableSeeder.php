<?php

use Illuminate\Database\Seeder;

class ParametrosGrupoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parametros_grupo')->insert([
            [
                'nome' =>'SERVIDOR',
                'ativo' => 'TRUE'
            ]
        ]);
    }
}
