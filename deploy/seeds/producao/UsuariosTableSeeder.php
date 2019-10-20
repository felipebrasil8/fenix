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
                'perfil_id' => 4,
                'nome' => 'ADONIAS SILVA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'adonias.silva',
                'funcionario_id' => 1,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'ADRIANO SANDES',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'adriano.sandes',
                'funcionario_id' => 2,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'ALINE LIMA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'aline.lima',
                'funcionario_id' => 3,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'PAULA RIBEIRO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'paula.ribeiro',
                'funcionario_id' => 4,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'ANA OLIVEIRA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'ana.oliveira',
                'funcionario_id' => 5,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'BRUNO LAUDILHO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'bruno.laudilho',
                'funcionario_id' => 6,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 3,
                'nome' => 'CIBELE GOMIDE',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'cibele.gomide',
                'funcionario_id' => 7,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'CLAUDIA SILVA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'claudia.silva',
                'funcionario_id' => 8,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'DALCY SILVA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'dalcy.silva',
                'funcionario_id' => 9,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'DÊNER OLIVEIRA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'dener.oliveira',
                'funcionario_id' => 10,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'ELEN JESUS',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'elen.jesus',
                'funcionario_id' => 11,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'ERICA OLIVEIRA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'erica.oliveira',
                'funcionario_id' => 12,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'ERICA SANTOS',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'erica.santos',
                'funcionario_id' => 13,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'ERIVALTER SANTANA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'erivalter.santana',
                'funcionario_id' => 14,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'EZEQUIEL ARAUJO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'ezequiel.araujo',
                'funcionario_id' => 15,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'FABIANE PAPAIS',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'fabiane.papais',
                'funcionario_id' => 16,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 1,
                'nome' => 'FABIO SANTOS',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'fabio.santos',
                'funcionario_id' => 17,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'FELIPE BRASIL',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'felipe.brasil',
                'funcionario_id' => 18,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'FERNANDA BAPTISTA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'fernanda.baptista',
                'funcionario_id' => 19,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'FILIPE CRESPO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'filipe.crespo',
                'funcionario_id' => 20,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 2,
                'nome' => 'GERSON PIRES',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'gerson.pires',
                'funcionario_id' => 21,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'GILBERTO CHAVES',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'gilberto.chaves',
                'funcionario_id' => 22,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 1,
                'nome' => 'GUSTAVO LOPES',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'gustavo.lopes',
                'funcionario_id' => 23,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'JHONANTHAN BAHIA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'jhonanthan.bahia',
                'funcionario_id' => 24,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'JOÃO DAVID',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'joao.david',
                'funcionario_id' => 25,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'JOSIMAR MACARIO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'josimar.macario',
                'funcionario_id' => 26,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'MARCIA OLIVEIRA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'marcia.oliveira',
                'funcionario_id' => 27,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'MARCOS MATSUDA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'marcos.matsuda',
                'funcionario_id' => 28,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'MAYLA TOLEDO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'mayla.toledo',
                'funcionario_id' => 29,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'MICHEL SANTOS',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'michel.santos',
                'funcionario_id' => 30,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'MICHELE DUARTE',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'michele.duarte',
                'funcionario_id' => 31,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'PAULO JUNIOR',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'paulo.junior',
                'funcionario_id' => 32,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'PEDRO CARVALHO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'pedro.carvalho',
                'funcionario_id' => 33,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'RAPHAEL MAIA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'raphael.maia',
                'funcionario_id' => 34,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'RUAN SANTANA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'ruan.santana',
                'funcionario_id' => 35,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'SKALAT SILVA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'skalat.silva',
                'funcionario_id' => 36,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'SOFIE PAPAIS',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'sofie.papais',
                'funcionario_id' => 37,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'SUELLEN DIAS',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'suellen.dias',
                'funcionario_id' => 38,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'VALDIK GUERRA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'valdik.guerra',
                'funcionario_id' => 39,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'NELSON GUERRA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'nelson.guerra',
                'funcionario_id' => 40,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'VANESSA ARAUJO',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'vanessa.araujo',
                'funcionario_id' => 41,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'VERÔNICA ALVES',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'veronica.alves',
                'funcionario_id' => 42,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'WAGNER ALVES',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'wagner.alves',
                'funcionario_id' => 43,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
            [
                'perfil_id' => 4,
                'nome' => 'EDIVANIA OLIVEIRA',
                'password' => bcrypt('Mudar@123'),
                'usuario' => 'edivania.oliveira',
                'funcionario_id' => 44,
                'senha_alterada' => false,
                'visualizado_senha_alterada' => false
            ],
        ]);
    }
}
