<?php

use Illuminate\Database\Seeder;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('usuarios')->insert([
            [
                'perfil_id' => 1,
                'nome' => 'GUSTAVO LOPES',
                'password' => bcrypt(123456),
                'usuario' => 'gustavo.lopes',
                'funcionario_id' => 1,
                'senha_alterada' => true,
                'visualizado_senha_alterada' => true
            ],
            [
                'perfil_id' => 1,
                'nome' => 'FELIPE BRASIL',
                'password' => bcrypt(123456),
                'usuario' => 'felipe.brasil',
                'funcionario_id' => 2,
                'senha_alterada' => true,
                'visualizado_senha_alterada' => true
            ],
            [
                'perfil_id' => 1,
                'nome' => 'MARCOS MATSUDA',
                'password' => bcrypt(123456),
                'usuario' => 'marcos.matsuda',
                'funcionario_id' => 3,
                'senha_alterada' => true,
                'visualizado_senha_alterada' => true
            ],
            [
                'perfil_id' => 1,
                'nome' => 'JOÃO DAVID',
                'password' => bcrypt(123456),
                'usuario' => 'joao.david',
                'funcionario_id' => 4,
                'senha_alterada' => true,
                'visualizado_senha_alterada' => true
            ],
            [
                'perfil_id' => 2,
                'nome' => 'GERSON PIRES',
                'password' => bcrypt(123456),
                'usuario' => 'gerson.pires',
                'funcionario_id' => 5,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 3,
                'nome' => 'CIBELE GOMIDE',
                'password' => bcrypt(123456),
                'usuario' => 'cibele.gomide',
                'funcionario_id' => 6,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 1,
                'nome' => 'FILIPE CRESPO',
                'password' => bcrypt(123456),
                'usuario' => 'filipe.crespo',
                'funcionario_id' => 7,
                'senha_alterada' => true,
                'visualizado_senha_alterada' => true
            ],
            [
                'perfil_id' => 4,
                'nome' => 'DÊNER OLIVEIRA',
                'password' => bcrypt(123456),
                'usuario' => 'dener.oliveira',
                'funcionario_id' => 8,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
        ]);
        

        
    }
}
