<?php

use Illuminate\Database\Seeder;

class AcessoPerfilSprint13TableSeeder extends Seeder
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
                'acesso_id' => 8,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 8,
                'perfil_id' => 3
            ],
            [
                'acesso_id' => 9,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 10,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 11,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 12,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 10,
                'perfil_id' => 2
            ],
            [
                'acesso_id' => 12,
                'perfil_id' => 2
            ],
            [
                'acesso_id' => 13,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 13,
                'perfil_id' => 2
            ],
        ]);
    }
}
