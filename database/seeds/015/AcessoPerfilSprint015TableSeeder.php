<?php

use Illuminate\Database\Seeder;

class AcessoPerfilSprint015TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => 14,
                'perfil_id' => 1
            ],
        ]);
    }
}
