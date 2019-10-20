<?php

use Illuminate\Database\Seeder;

class FuncionariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcionarios')->insert([
            [
                'nome' => 'GUSTAVO LOPES',
                'nome_completo' => 'GUSTAVO LOPES',
                'email' => 'gustavo.lopes@novaxtelecom.com.br',
                'dt_nascimento' => '15-01-1987',
                'avatar' => '1',
                'celular_pessoal' => '11987521368',
                'celular_corporativo' => '11987521368',
                'telefone_comercial' => '1131327003',
                'ramal' => '7003'
            ],
            [
                'nome' => 'FELIPE BRASIL',
                'nome_completo' => 'FELIPE BRASIL',
                'email' => 'felipe.brasil@novaxtelecom.com.br',
                'dt_nascimento' => '27-06-1993',
                'avatar' => '2',
                'celular_pessoal' => '11981421465',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327013',
                'ramal' => '7013'
            ],
            [
                'nome' => 'MARCOS MATSUDA',
                'nome_completo' => 'MARCOS MATSUDA',
                'email' => 'marcos.matsuda@novaxtelecom.com.br',
                'dt_nascimento' => '20-09-1985',
                'avatar' => '3',
                'celular_pessoal' => '11989052356',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327011',
                'ramal' => '7011'
            ],
            [
                'nome' => 'JOÃO DAVID',
                'nome_completo' => 'JOÃO DAVID',
                'email' => 'joao.david@novaxtelecom.com.br',
                'dt_nascimento' => '03-02-1988',
                'avatar' => '4',
                'celular_pessoal' => '11972460095',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327018',
                'ramal' => '7018'
            ],
            [
                'nome' => 'GERSON PIRES',
                'nome_completo' => 'GERSON PIRES',
                'email' => 'gerson.pires@novaxtelecom.com.br',
                'dt_nascimento' => '20-06-1990',
                'avatar' => '5',
                'celular_pessoal' => '11997481905',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327019',
                'ramal' => '7019'
            ],
            [
                'nome' => 'CIBELE GOMIDE',
                'nome_completo' => 'CIBELE GOMIDE',
                'email' => 'cibele.gomide@novaxtelecom.com.br',
                'dt_nascimento' => '21-01-1986',
                'avatar' => '6',
                'celular_pessoal' => '11971095153',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327004',
                'ramal' => '7004'
            ],
            [
                'nome' => 'FILIPE CRESPO',
                'nome_completo' => 'FILIPE CRESPO',
                'email' => 'filipe.crespo@novaxtelecom.com.br',
                'dt_nascimento' => '02-05-1993',
                'avatar' => '7',
                'celular_pessoal' => '11984985171',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327006',
                'ramal' => '7006'
            ],
            [
                'nome' => 'DÊNER OLIVEIRA',
                'nome_completo' => 'DÊNER OLIVEIRA',
                'email' => 'dener.oliveira@novaxtelecom.com.br',
                'dt_nascimento' => '1998-11-10',
                'avatar' => '8',
                'celular_pessoal' => '11971895464',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327023',
                'ramal' => '7023'
            ],
        ]);
    }
}
