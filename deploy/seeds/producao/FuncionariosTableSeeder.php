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
                'nome' => 'ADONIAS SILVA',
                'nome_completo' => 'ADONIAS BARROS DA SILVA',
                'email' => 'adonias.silva@novaxtelecom.com.br',
                'dt_nascimento' => '22-04-1983',
                'avatar' => '1',
                'celular_pessoal' => '11982074828',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327012',
                'ramal' => '7012'
            ],
            [
                'nome' => 'ADRIANO SANDES',
                'nome_completo' => 'ADRIANO NOVAIS SANDES',
                'email' => 'adriano.sandes@novaxtelecom.com.br',
                'dt_nascimento' => '28-06-1982',
                'avatar' => '2',
                'celular_pessoal' => '11982136375',
                'celular_corporativo' => '11968843977',
                'telefone_comercial' => '1131327000',
                'ramal' => '7024'
            ],
            [
                'nome' => 'ALINE LIMA',
                'nome_completo' => 'ALINE RAMOS LIMA',
                'email' => 'aline.lima@novaxtelecom.com.br',
                'dt_nascimento' => '31-03-1989',
                'avatar' => '3',
                'celular_pessoal' => '11946723103',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327030',
                'ramal' => '7030'
            ],
            [
                'nome' => 'PAULA RIBEIRO',
                'nome_completo' => 'ANA PAULA GIUBILEI RIBEIRO GONÇALVES',
                'email' => 'paula.ribeiro@novaxtelecom.com.br',
                'dt_nascimento' => '24-03-1991',
                'avatar' => '4',
                'celular_pessoal' => '11969622189',
                'celular_corporativo' => '11941782666',
                'telefone_comercial' => '1131327038',
                'ramal' => '7038'
            ],
            [
                'nome' => 'ANA OLIVEIRA',
                'nome_completo' => 'ANA PAULA MOTA FEITOSA DE OLIVEIRA',
                'email' => 'ana.oliveira@novaxtelecom.com.br',
                'dt_nascimento' => '27-04-1984',
                'avatar' => '5',
                'celular_pessoal' => '11984442965',
                'celular_corporativo' => '11966711560',
                'telefone_comercial' => '1131327036',
                'ramal' => '7036'
            ],
            [
                'nome' => 'BRUNO LAUDILHO',
                'nome_completo' => 'BRUNO LAUDILHO DE MOURA',
                'email' => 'bruno.laudilho@novaxtelecom.com.br',
                'dt_nascimento' => '12-10-1989',
                'avatar' => '6',
                'celular_pessoal' => '11985452027',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327080',
                'ramal' => '8316'
            ],
            [
                'nome' => 'CIBELE GOMIDE',
                'nome_completo' => 'CIBELE PACHECO GOMIDE',
                'email' => 'cibele.gomide@novaxtelecom.com.br',
                'dt_nascimento' => '21-01-1986',
                'avatar' => '7',
                'celular_pessoal' => '11971095153',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327004',
                'ramal' => '7004'
            ],
            [
                'nome' => 'CLAUDIA SILVA',
                'nome_completo' => 'CLAUDIA DE OLIVEIRA SILVA',
                'email' => 'claudia.silva@novaxtelecom.com.br',
                'dt_nascimento' => '28-12-1977',
                'avatar' => '8',
                'celular_pessoal' => '11994775882',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327020',
                'ramal' => '7020'
            ],
            [
                'nome' => 'DALCY SILVA',
                'nome_completo' => 'DALCY DOURADO DE SOUZA SILVA',
                'email' => 'dalcy.silva@novaxtelecom.com.br',
                'dt_nascimento' => '17-09-1964',
                'avatar' => '9',
                'celular_pessoal' => '11999106912',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327000',
                'ramal' => '8004'
            ],
            [
                'nome' => 'DÊNER OLIVEIRA',
                'nome_completo' => 'DÊNER OLIVEIRA DE SANTANA',
                'email' => 'dener.oliveira@novaxtelecom.com.br',
                'dt_nascimento' => '10-11-1998',
                'avatar' => '10',
                'celular_pessoal' => '11971895464',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327023',
                'ramal' => '7023'
            ],
            [
                'nome' => 'ELEN JESUS',
                'nome_completo' => 'ELENILDE MARIA DE JESUS',
                'email' => 'elen.jesus@novaxtelecom.com.br',
                'dt_nascimento' => '30-04-1985',
                'avatar' => '11',
                'celular_pessoal' => '11998035918',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327000',
                'ramal' => '7000'
            ],
            [
                'nome' => 'ERICA OLIVEIRA',
                'nome_completo' => 'ERICA JESUS DE OLIVEIRA',
                'email' => 'erica.oliveira@novaxtelecom.com.br',
                'dt_nascimento' => '10-07-1986',
                'avatar' => '12',
                'celular_pessoal' => '11980772338',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327014',
                'ramal' => '7014'
            ],
            [
                'nome' => 'ERICA SANTOS',
                'nome_completo' => 'ERICA QUEIROZ DOS SANTOS',
                'email' => 'erica.santos@novaxtelecom.com.br',
                'dt_nascimento' => '21-11-1986',
                'avatar' => '13',
                'celular_pessoal' => '11954436869',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327005',
                'ramal' => '7005'
            ],
            [
                'nome' => 'ERIVALTER SANTANA',
                'nome_completo' => 'ERIVALTER OLIVEIRA DE SANTANA',
                'email' => 'erivalter.santana@novaxtelecom.com.br',
                'dt_nascimento' => '28-04-1988',
                'avatar' => '14',
                'celular_pessoal' => '11980160777',
                'celular_corporativo' => '11975468077',
                'telefone_comercial' => '1131327000',
                'ramal' => '7024'
            ],
            [
                'nome' => 'EZEQUIEL ARAUJO',
                'nome_completo' => 'EZEQUIEL SANTOS ARAUJO',
                'email' => 'ezequiel.araujo@novaxtelecom.com.br',
                'dt_nascimento' => '20-09-1991',
                'avatar' => '15',
                'celular_pessoal' => '11982988323',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327022',
                'ramal' => '7022'
            ],
            [
                'nome' => 'FABIANE PAPAIS',
                'nome_completo' => 'FABIANE VAZ PAPAIS',
                'email' => 'fabiane.papais@novaxtelecom.com.br',
                'dt_nascimento' => '05-09-1990',
                'avatar' => '16',
                'celular_pessoal' => '11997040115',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327080',
                'ramal' => '8314'
            ],
            [
                'nome' => 'FABIO SANTOS',
                'nome_completo' => 'FÁBIO DE OLIVEIRA SANTOS',
                'email' => 'fabio.santos@novaxtelecom.com.br',
                'dt_nascimento' => '23-01-1983',
                'avatar' => '17',
                'celular_pessoal' => '11983779544',
                'celular_corporativo' => '11983779544',
                'telefone_comercial' => '1131327001',
                'ramal' => '7001'
            ],
            [
                'nome' => 'FELIPE BRASIL',
                'nome_completo' => 'FELIPE BRASIL GOUVEA DE ALMEIDA',
                'email' => 'felipe.brasil@novaxtelecom.com.br',
                'dt_nascimento' => '27-06-1993',
                'avatar' => '18',
                'celular_pessoal' => '11981421465',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327013',
                'ramal' => '7013'
            ],
            [
                'nome' => 'FERNANDA BAPTISTA',
                'nome_completo' => 'FERNANDA MARTINS BAPTISTA',
                'email' => 'fernanda.baptista@novaxtelecom.com.br',
                'dt_nascimento' => '17-10-1988',
                'avatar' => '19',
                'celular_pessoal' => '11959518924',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327000',
                'ramal' => '8201'
            ],
            [
                'nome' => 'FILIPE CRESPO',
                'nome_completo' => 'FILIPE SOARES CRESPO',
                'email' => 'filipe.crespo@novaxtelecom.com.br',
                'dt_nascimento' => '02-05-1993',
                'avatar' => '20',
                'celular_pessoal' => '11984985171',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327006',
                'ramal' => '7006'
            ],
            [
                'nome' => 'GERSON PIRES',
                'nome_completo' => 'GERSON THIAGO PIRES CARNEIRO',
                'email' => 'gerson.pires@novaxtelecom.com.br',
                'dt_nascimento' => '20-06-1990',
                'avatar' => '21',
                'celular_pessoal' => '11997481905',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327019',
                'ramal' => '7019'
            ],
            [
                'nome' => 'GILBERTO CHAVES',
                'nome_completo' => 'GILBERTO PEREIRA CHAVES JUNIOR',
                'email' => 'gilberto.chaves@novaxtelecom.com.br',
                'dt_nascimento' => '12-09-1991',
                'avatar' => '22',
                'celular_pessoal' => '11995876134',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327010',
                'ramal' => '7010'
            ],
            [
                'nome' => 'GUSTAVO LOPES',
                'nome_completo' => 'GUSTAVO DOS SANTOS LOPES',
                'email' => 'gustavo.lopes@novaxtelecom.com.br',
                'dt_nascimento' => '15-01-1987',
                'avatar' => '23',
                'celular_pessoal' => '11987521368',
                'celular_corporativo' => '11987521368',
                'telefone_comercial' => '1131327003',
                'ramal' => '7003'
            ],
            [
                'nome' => 'JHONANTHAN BAHIA',
                'nome_completo' => 'JHONANTHAN EDUARDO COSTA BAHIA',
                'email' => 'jhonanthan.bahia@novaxtelecom.com.br',
                'dt_nascimento' => '27-10-1989',
                'avatar' => '24',
                'celular_pessoal' => '11950777184',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327080',
                'ramal' => '8315'
            ],
            [
                'nome' => 'JOÃO DAVID',
                'nome_completo' => 'JOÃO LUIZ FERNANDES DAVID',
                'email' => 'joao.david@novaxtelecom.com.br',
                'dt_nascimento' => '03-02-1988',
                'avatar' => '25',
                'celular_pessoal' => '11972460095',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327018',
                'ramal' => '7018'
            ],
            [
                'nome' => 'JOSIMAR MACARIO',
                'nome_completo' => 'JOSIMAR MACARIO LIMA',
                'email' => 'josimar.macario@novaxtelecom.com.br',
                'dt_nascimento' => '04-11-1965',
                'avatar' => '26',
                'celular_pessoal' => '11996913798',
                'celular_corporativo' => '11996913798',
                'telefone_comercial' => '1131327016',
                'ramal' => '7016'
            ],
            [
                'nome' => 'MARCIA OLIVEIRA',
                'nome_completo' => 'MARCIA OLIVEIRA SANTOS MACIEL',
                'email' => 'marcia.oliveira@novaxtelecom.com.br',
                'dt_nascimento' => '06-01-1995',
                'avatar' => '27',
                'celular_pessoal' => '11998498975',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327021',
                'ramal' => '7021'
            ],
            [
                'nome' => 'MARCOS MATSUDA',
                'nome_completo' => 'MARCOS VINICIUS SILVESTRE MATSUDA',
                'email' => 'marcos.matsuda@novaxtelecom.com.br',
                'dt_nascimento' => '20-09-1985',
                'avatar' => '28',
                'celular_pessoal' => '11989052356',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327011',
                'ramal' => '7011'
            ],
            [
                'nome' => 'MAYLA TOLEDO',
                'nome_completo' => 'MAYLA BOBADILLO TOLEDO FINATTO',
                'email' => 'mayla.toledo@novaxtelecom.com.br',
                'dt_nascimento' => '05-06-1987',
                'avatar' => '29',
                'celular_pessoal' => '11956508081',
                'celular_corporativo' => '11964124361',
                'telefone_comercial' => '1131327033',
                'ramal' => '7033'
            ],
            [
                'nome' => 'MICHEL SANTOS',
                'nome_completo' => 'MICHEL CARVALHO DOS SANTOS',
                'email' => 'michel.santos@novaxtelecom.com.br',
                'dt_nascimento' => '01-09-1990',
                'avatar' => '30',
                'celular_pessoal' => '11964511202',
                'celular_corporativo' => '11998099606',
                'telefone_comercial' => '1131327000',
                'ramal' => '7024'
            ],
            [
                'nome' => 'MICHELE DUARTE',
                'nome_completo' => 'MICHELE DUARTE DREGEDIO DE JESUS',
                'email' => 'michele.duarte@novaxtelecom.com.br',
                'dt_nascimento' => '17-01-1986',
                'avatar' => '31',
                'celular_pessoal' => '11966686029',
                'celular_corporativo' => '11966686029',
                'telefone_comercial' => '1131327034',
                'ramal' => '7034'
            ],
            [
                'nome' => 'PAULO JUNIOR',
                'nome_completo' => 'PAULO ALVES DE OLIVEIRA JUNIOR',
                'email' => 'paulo.junior@novaxtelecom.com.br',
                'dt_nascimento' => '25-04-1982',
                'avatar' => '32',
                'celular_pessoal' => '11971540588',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327017',
                'ramal' => '7017'
            ],
            [
                'nome' => 'PEDRO CARVALHO',
                'nome_completo' => 'PEDRO IVO SANTANA DE CARVALHO',
                'email' => 'pedro.carvalho@novaxtelecom.com.br',
                'dt_nascimento' => '03-06-1988',
                'avatar' => '33',
                'celular_pessoal' => '11993773997',
                'celular_corporativo' => '11973565218',
                'telefone_comercial' => '1131327015',
                'ramal' => '7015'
            ],
            [
                'nome' => 'RAPHAEL MAIA',
                'nome_completo' => 'RAPHAEL NASCIMENTO MAIA',
                'email' => 'raphael.maia@novaxtelecom.com.br',
                'dt_nascimento' => '17-06-1982',
                'avatar' => '34',
                'celular_pessoal' => '11941525657',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327024',
                'ramal' => '7024'
            ],
            [
                'nome' => 'RUAN SANTANA',
                'nome_completo' => 'RUAN CARLOS DE OLIVEIRA SANTANA',
                'email' => 'ruan.santana@novaxtelecom.com.br',
                'dt_nascimento' => '26-03-1989',
                'avatar' => '35',
                'celular_pessoal' => '11942392965',
                'celular_corporativo' => '11966711303',
                'telefone_comercial' => '1131327000',
                'ramal' => '7024'
            ],
            [
                'nome' => 'SKALAT SILVA',
                'nome_completo' => 'SKALAT IRENE DA SILVA',
                'email' => 'skalat.silva@novaxtelecom.com.br',
                'dt_nascimento' => '29-02-1992',
                'avatar' => '36',
                'celular_pessoal' => '11967285846',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327000',
                'ramal' => '8207'
            ],
            [
                'nome' => 'SOFIE PAPAIS',
                'nome_completo' => 'SOFIE VAZ PAPAIS',
                'email' => 'sofie.papais@novaxtelecom.com.br',
                'dt_nascimento' => '28-02-1993',
                'avatar' => '37',
                'celular_pessoal' => '11961885413',
                'celular_corporativo' => '11964894439',
                'telefone_comercial' => '1131327008',
                'ramal' => '7008'
            ],
            [
                'nome' => 'SUELLEN DIAS',
                'nome_completo' => 'SUELLEN DIAS SOUSA OLIVEIRA',
                'email' => 'suellen.dias@novaxtelecom.com.br',
                'dt_nascimento' => '20-06-1990',
                'avatar' => '38',
                'celular_pessoal' => '11982480108',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327007',
                'ramal' => '7007'
            ],
            [
                'nome' => 'VALDIK GUERRA',
                'nome_completo' => 'VALDIK GUERRA LIMA',
                'email' => 'valdik.guerra@novaxtelecom.com.br',
                'dt_nascimento' => '12-10-1963',
                'avatar' => '39',
                'celular_pessoal' => '11964707222',
                'celular_corporativo' => '11964707222',
                'telefone_comercial' => '1131327009',
                'ramal' => '7009'
            ],
            [
                'nome' => 'NELSON GUERRA',
                'nome_completo' => 'VALDINELSON GUERRA LIMA',
                'email' => 'nelson.guerra@novaxtelecom.com.br',
                'dt_nascimento' => '02-11-1964',
                'avatar' => '40',
                'celular_pessoal' => '11984577364',
                'celular_corporativo' => '11984577364',
                'telefone_comercial' => '1131327002',
                'ramal' => '7002'
            ],
            [
                'nome' => 'VANESSA ARAUJO',
                'nome_completo' => 'VANESSA MARQUES DE ARAUJO',
                'email' => 'vanessa.araujo@novaxtelecom.com.br',
                'dt_nascimento' => '20-06-1990',
                'avatar' => '41',
                'celular_pessoal' => '11977836186',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327000',
                'ramal' => '8205'
            ],
            [
                'nome' => 'VERÔNICA ALVES',
                'nome_completo' => 'VERÔNICA FIGUEIREDO ALVES',
                'email' => 'veronica.alves@novaxtelecom.com.br',
                'dt_nascimento' => '06-04-1983',
                'avatar' => '42',
                'celular_pessoal' => '11966726277',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327080',
                'ramal' => '8309'
            ],
            [
                'nome' => 'WAGNER ALVES',
                'nome_completo' => 'WAGNER ALVES DE OLIVEIRA',
                'email' => 'wagner.alves@novaxtelecom.com.br',
                'dt_nascimento' => '22-11-1978',
                'avatar' => '43',
                'celular_pessoal' => '11979660504',
                'celular_corporativo' => '11966711573',
                'telefone_comercial' => '1131327025',
                'ramal' => '7025'
            ],
            [
                'nome' => 'EDIVANIA OLIVEIRA',
                'nome_completo' => 'EDIVANIA OLIVEIRA SANTOS',
                'email' => 'edivania.oliveira@novaxtelecom.com.br',
                'dt_nascimento' => '26-08-1976',
                'avatar' => '44',
                'celular_pessoal' => '11964858241',
                'celular_corporativo' => '',
                'telefone_comercial' => '1131327000',
                'ramal' => '8005'
            ],
        ]);
    }
}
