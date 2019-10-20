<?php

use Illuminate\Database\Seeder;

class ParametrosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parametros')->insert([
            [
                'parametro_grupo_id' => 1,
                'parametro_tipo_id' => 1,
                'nome' => 'VERSAO',
                'descricao' => 'VERSÃO DO SISTEMA',
                'valor_texto' => '1.0.07',
                'valor_numero' => NULL,
                'valor_booleano' => NULL,
                'ordem' => 1000,
                'editar' => false,
                'obrigatorio' => true
            ],
            [
                'parametro_grupo_id' => 1,
                'parametro_tipo_id' => 1,
                'nome' => 'COMPLEMENTO_LOGO',
                'descricao' => 'NOME COMPLEMENTAR DO LOGO PARA DATAS ESPECIAIS',
                'valor_texto' => '',
                'valor_numero' => NULL,
                'valor_booleano' => NULL,
                'ordem' => 1100,
                'editar' => true,
                'obrigatorio' => false
            ],
        ]);
    }
}
