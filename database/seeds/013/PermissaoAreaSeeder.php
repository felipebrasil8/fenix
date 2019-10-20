<?php

use Illuminate\Database\Seeder;

class PermissaoAreaSeeder extends Seeder
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
                'menu_id' => 13,
                'descricao' => 'CADASTRAR ÁREA',
                'identificador' => 'RH_AREA_CADASTRAR'
            ],
            [
                'menu_id' => 13,
                'descricao' => 'EDITAR ÁREA',
                'identificador' => 'RH_AREA_EDITAR'
            ],
            [
                'menu_id' => 13,
                'descricao' => 'STATUS ÁREA',
                'identificador' => 'RH_AREA_STATUS'
            ],
            [
                'menu_id' => 13,
                'descricao' => 'VISUALIZAR ÁREA',
                'identificador' => 'RH_AREA_VISUALIZAR'
            ]
        ]);

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 8,
                'permissao_id' => 31
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 32
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 33
            ],
            [
                'acesso_id' => 8,
                'permissao_id' => 34
            ]
        ]);
    }
}
