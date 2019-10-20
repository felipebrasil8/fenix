<?php

use Illuminate\Database\Seeder;

class PermissaoCargoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissoes')->insert([
            [
                'menu_id' => 15,
                'descricao' => 'CADASTRAR CARGO',
                'identificador' => 'RH_CARGO_CADASTRAR'
            ],
            [
                'menu_id' => 15,
                'descricao' => 'EDITAR CARGO',
                'identificador' => 'RH_CARGO_EDITAR'
            ],
            [
                'menu_id' => 15,
                'descricao' => 'STATUS CARGO',
                'identificador' => 'RH_CARGO_STATUS'
            ],
            [
                'menu_id' => 15,
                'descricao' => 'VISUALIZAR CARGO',
                'identificador' => 'RH_CARGO_VISUALIZAR'
            ]
        ]);

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 8,
                'permissao_id' => 39
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 40
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 41
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 42
            ]
        ]);
    }
}