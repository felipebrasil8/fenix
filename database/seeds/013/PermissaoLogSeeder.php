<?php

use Illuminate\Database\Seeder;

class PermissaoLogSeeder extends Seeder
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
                'menu_id' => 11,
                'descricao' => 'VISUALIZAR LOG LOGIN E LOGOUT',
                'identificador' => 'LOG_LOGIN_VISUALIZAR'
            ]
        ]);

        DB::table('acesso_permissao')->insert([
            [
                'acesso_id' => 7,
                'permissao_id' => 28
            ]
        ]);
    }
}
