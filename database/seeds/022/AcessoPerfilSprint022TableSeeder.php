<?php

use Illuminate\Database\Seeder;

class AcessoPerfilSprint022TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $acesso_id = DB::table('acessos')->where('nome', 'BASE DE CONHECIMENTO - VISUALIZAR MENSAGENS')->get()->first()->id;

        $perfil_adm = DB::table('perfis')->where('nome', 'ADMINISTRADOR')->get()->first()->id;
        $perfil_gp = DB::table('perfis')->where('nome', 'GESTÃO DE PESSOAS')->get()->first()->id;
        $perfil_gc = DB::table('perfis')->where('nome', 'GESTÃO DO CONHECIMENTO')->get()->first()->id;
        
        // Vincular perfis com os acessos corretos
        DB::table('acesso_perfil')->insert([
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_adm
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_gp
            ],
            [
                'acesso_id' => $acesso_id,
                'perfil_id' => $perfil_gc
            ],
        ]);
    }
}