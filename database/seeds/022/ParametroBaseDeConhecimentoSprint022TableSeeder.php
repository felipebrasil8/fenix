<?php

use Illuminate\Database\Seeder;

class ParametroBaseDeConhecimentoSprint022TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cria o grupo BASE DE CONHECIMENTO
        DB::table('parametros_grupo')->insert([
            [
                'nome' => 'BASE DE CONHECIMENTO'
            ]
        ]);

        //busca o id do grupo BASE DE CONHECIMENTO
        $grupo_id = DB::table('parametros_grupo')->where('nome', 'BASE DE CONHECIMENTO')->get()->first()->id;

        //busca o id do tipo de parametro
        $tipo_id = DB::table('parametros_tipo')->where('nome', 'TEXTO')->get()->first()->id;

        //busca o id do departamento BASE DE CONHECIMENTO
        $departamento_id = DB::table('departamentos')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;

        //criar um novo parâmetro para armazenar quais departamentos receberão a notificação
        DB::table('parametros')->insert([
            [
                'parametro_grupo_id' => $grupo_id,
                'parametro_tipo_id' => $tipo_id,
                'nome' => 'BC_NOTIFICACAO_DEPARTAMENTOS',
                'descricao' => 'ID DOS DEPARTAMENTOS QUE RECEBERÃO AS NOTIFICAÇÕES DA BASE DE CONHECIMENTO',
                'valor_texto' => $departamento_id,
                'ordem' => 2100,
                'editar' => true,
            ]
        ]);
    }
}
