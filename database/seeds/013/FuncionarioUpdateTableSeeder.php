<?php

use Illuminate\Database\Seeder;

class FuncionarioUpdateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('funcionarios')->where('nome', 'GUSTAVO LOPES')->update([
        	'cargo_id' => 1,
        	'gestor_id' => null,
        ]);

        DB::table('funcionarios')->where('nome', 'JOÃO DAVID')->update([
        	'cargo_id' => 1,
        	'gestor_id' => 1,
        ]);

        DB::table('funcionarios')->where('nome', 'MARCOS MATSUDA')->update([
        	'cargo_id' => 1,
        	'gestor_id' => 4,
        ]);

        DB::table('funcionarios')->where('nome', 'FELIPE BRASIL')->update([
        	'cargo_id' => 1,
        	'gestor_id' => 4,
        ]);

        DB::table('funcionarios')->where('nome', 'FILIPE CRESPO')->update([
        	'cargo_id' => 1,
        	'gestor_id' => 4,
        ]);

        DB::table('funcionarios')->where('nome', 'GERSON PIRES')->update([
        	'cargo_id' => 3,
        	'gestor_id' => 1,
        ]);

        DB::table('funcionarios')->where('nome', 'DÊNER OLIVEIRA')->update([
        	'cargo_id' => 3,
        	'gestor_id' => 1,
        ]);

        DB::table('funcionarios')->where('nome', 'CIBELE GOMIDE')->update([
        	'cargo_id' => 2,
        	'gestor_id' => 1,
        ]);


    }
}
