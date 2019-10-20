<?php

use Illuminate\Database\Seeder;

class AcessoPerfilTableSeeder extends Seeder
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
                'acesso_id' => 1,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 2,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 3,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 4,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 5,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 5,
                'perfil_id' => 2
            ],
            [
                'acesso_id' => 2,
                'perfil_id' => 2
            ],
            [
                'acesso_id' => 3,
                'perfil_id' => 3
            ],
            [
                'acesso_id' => 5,
                'perfil_id' => 3
            ],
            [
                'acesso_id' => 5,
                'perfil_id' => 4
            ],
            [
                'acesso_id' => 6,
                'perfil_id' => 1
            ],
            [
                'acesso_id' => 7,
                'perfil_id' => 1
            ],
        ]);
    }
}
