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

        DB::table('funcionarios')->where('nome', 'NELSON GUERRA')->update([
        	'cargo_id' => 1,
        	'gestor_id' => null,
        ]);

        DB::table('funcionarios')->where('nome', 'ERICA OLIVEIRA')->update([
        	'cargo_id' => 2,
        	'gestor_id' => 40,
        ]);

        DB::table('funcionarios')->where('nome', 'MARCIA OLIVEIRA')->update([
        	'cargo_id' => 2,
        	'gestor_id' => 40,
        ]);

        DB::table('funcionarios')->where('nome', 'ELEN JESUS')->update([
        	'cargo_id' => 3,
        	'gestor_id' => 40,
        ]);

        DB::table('funcionarios')->where('nome', 'DALCY SILVA')->update([
        	'cargo_id' => 4,
        	'gestor_id' => 40,
        ]);

        DB::table('funcionarios')->where('nome', 'EDIVANIA OLIVEIRA')->update([
        	'cargo_id' => 4,
        	'gestor_id' => 40,
        ]);

        DB::table('funcionarios')->where('nome', 'VALDIK GUERRA')->update([
        	'cargo_id' => 5,
        	'gestor_id' => null,
        ]);

        DB::table('funcionarios')->where('nome', 'CLAUDIA SILVA')->update([
        	'cargo_id' => 6,
        	'gestor_id' => 39,
        ]);

        DB::table('funcionarios')->where('nome', 'ALINE LIMA')->update([
        	'cargo_id' => 7,
        	'gestor_id' => 8,
        ]);

        DB::table('funcionarios')->where('nome', 'FERNANDA BAPTISTA')->update([
        	'cargo_id' => 8,
        	'gestor_id' => 8,
        ]);

        DB::table('funcionarios')->where('nome', 'SKALAT SILVA')->update([
        	'cargo_id' => 8,
        	'gestor_id' => 8,
        ]);

        DB::table('funcionarios')->where('nome', 'VANESSA ARAUJO')->update([
        	'cargo_id' => 8,
        	'gestor_id' => 8,
        ]);

        DB::table('funcionarios')->where('nome', 'ANA OLIVEIRA')->update([
        	'cargo_id' => 9,
        	'gestor_id' => 39,
        ]);

        DB::table('funcionarios')->where('nome', 'PAULA RIBEIRO')->update([
        	'cargo_id' => 10,
        	'gestor_id' => 5,
        ]);

        DB::table('funcionarios')->where('nome', 'MAYLA TOLEDO')->update([
        	'cargo_id' => 10,
        	'gestor_id' => 5,
        ]);

        DB::table('funcionarios')->where('nome', 'MICHELE DUARTE')->update([
        	'cargo_id' => 10,
        	'gestor_id' => 5,
        ]);

        DB::table('funcionarios')->where('nome', 'JOSIMAR MACARIO')->update([
        	'cargo_id' => 11,
        	'gestor_id' => null,
        ]);

        DB::table('funcionarios')->where('nome', 'MICHEL SANTOS')->update([
        	'cargo_id' => 12,
        	'gestor_id' => 26,
        ]);

        DB::table('funcionarios')->where('nome', 'ADRIANO SANDES')->update([
        	'cargo_id' => 12,
        	'gestor_id' => 26,
        ]);

        DB::table('funcionarios')->where('nome', 'ERIVALTER SANTANA')->update([
        	'cargo_id' => 12,
        	'gestor_id' => 26,
        ]);

        DB::table('funcionarios')->where('nome', 'RUAN SANTANA')->update([
        	'cargo_id' => 12,
        	'gestor_id' => 26,
        ]);

        DB::table('funcionarios')->where('nome', 'PEDRO CARVALHO')->update([
        	'cargo_id' => 13,
        	'gestor_id' => 26,
        ]);

        DB::table('funcionarios')->where('nome', 'RAPHAEL MAIA')->update([
        	'cargo_id' => 14,
        	'gestor_id' => 33,
        ]);

        DB::table('funcionarios')->where('nome', 'GUSTAVO LOPES')->update([
        	'cargo_id' => 15,
        	'gestor_id' => null,
        ]);

        DB::table('funcionarios')->where('nome', 'JOÃO DAVID')->update([
        	'cargo_id' => 16,
        	'gestor_id' => 23,
        ]);

        DB::table('funcionarios')->where('nome', 'MARCOS MATSUDA')->update([
        	'cargo_id' => 17,
        	'gestor_id' => 25,
        ]);

        DB::table('funcionarios')->where('nome', 'FELIPE BRASIL')->update([
        	'cargo_id' => 17,
        	'gestor_id' => 25,
        ]);

        DB::table('funcionarios')->where('nome', 'FILIPE CRESPO')->update([
        	'cargo_id' => 17,
        	'gestor_id' => 25,
        ]);

        DB::table('funcionarios')->where('nome', 'GERSON PIRES')->update([
        	'cargo_id' => 18,
        	'gestor_id' => 23,
        ]);

        DB::table('funcionarios')->where('nome', 'DÊNER OLIVEIRA')->update([
        	'cargo_id' => 19,
        	'gestor_id' => 21,
        ]);

        DB::table('funcionarios')->where('nome', 'PAULO JUNIOR')->update([
        	'cargo_id' => 20,
        	'gestor_id' => 23,
        ]);

        DB::table('funcionarios')->where('nome', 'ADONIAS SILVA')->update([
        	'cargo_id' => 21,
        	'gestor_id' => 32,
        ]);

        DB::table('funcionarios')->where('nome', 'EZEQUIEL ARAUJO')->update([
        	'cargo_id' => 21,
        	'gestor_id' => 32,
        ]);

        DB::table('funcionarios')->where('nome', 'FABIO SANTOS')->update([
        	'cargo_id' => 22,
        	'gestor_id' => null,
        ]);

        DB::table('funcionarios')->where('nome', 'CIBELE GOMIDE')->update([
        	'cargo_id' => 23,
        	'gestor_id' => 17,
        ]);

        DB::table('funcionarios')->where('nome', 'SOFIE PAPAIS')->update([
        	'cargo_id' => 24,
        	'gestor_id' => 7,
        ]);

        DB::table('funcionarios')->where('nome', 'SUELLEN DIAS')->update([
        	'cargo_id' => 25,
        	'gestor_id' => 7,
        ]);

        DB::table('funcionarios')->where('nome', 'GILBERTO CHAVES')->update([
        	'cargo_id' => 26,
        	'gestor_id' => 17,
        ]);

        DB::table('funcionarios')->where('nome', 'ERICA SANTOS')->update([
        	'cargo_id' => 27,
        	'gestor_id' => 17,
        ]);

        DB::table('funcionarios')->where('nome', 'BRUNO LAUDILHO')->update([
        	'cargo_id' => 28,
        	'gestor_id' => 12,
        ]);

        DB::table('funcionarios')->where('nome', 'FABIANE PAPAIS')->update([
        	'cargo_id' => 28,
        	'gestor_id' => 13,
        ]);

        DB::table('funcionarios')->where('nome', 'JHONANTHAN BAHIA')->update([
        	'cargo_id' => 28,
        	'gestor_id' => 13,
        ]);

        DB::table('funcionarios')->where('nome', 'VERÔNICA ALVES')->update([
        	'cargo_id' => 28,
        	'gestor_id' => 13,
        ]);

        DB::table('funcionarios')->where('nome', 'WAGNER ALVES')->update([
        	'cargo_id' => 29,
        	'gestor_id' => 17,
        ]);


    }
}
