<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Popula tabela usuarios com factories
        $this->call(FuncionariosTableSeeder::class);        
        $this->call(PerfisTableSeeder::class);        
        $this->call(UsuariosTableSeeder::class);        
        $this->call(AcessosTableSeeder::class);        
        $this->call(AcessoPerfilTableSeeder::class);        
        $this->call(MenusTableSeeder::class);        
        $this->call(ParametrosTipoTableSeeder::class);
        $this->call(ParametrosGrupoTableSeeder::class);    
        $this->call(ParametrosTableSeeder::class);
        $this->call(PermissaoAcessosSeeder::class); 
        $this->call(PermissaoPerfisSeeder::class); 
        $this->call(PermissaoUsuarioSeeder::class); 
        $this->call(PermissaoSistemaSeeder::class); 
        $this->call(PermissaoFuncionarioSeeder::class); 
        $this->call(PermissaoLogSeeder::class); 
        $this->call(PermissaoPoliticaSenhaSeeder::class);
        
        //sprint-013
        $this->call(MenusSprint013TableSeeder::class);        
        $this->call(AcessoSprint13TableSeeder::class);
        $this->call(AcessoPerfilSprint13TableSeeder::class);
        $this->call(AreaTableSeeder::class);
        $this->call(PermissaoAreaSeeder::class);
        $this->call(DepartamentoTableSeeder::class);
        $this->call(PermissaoDepartamentoTableSeeder::class);
        $this->call(PermissaoCargoTableSeeder::class);
        $this->call(CargoTableSeeder::class);
        $this->call(TicketPrioridadeTableSeeder::class);        
        $this->call(TicketStatusTableSeeder::class); 
        $this->call(TicketCamposTableSeeder::class);
        $this->call(PermissaoTicketSprint013TableSeeder::class); 
        $this->call(PermissaoFuncionarioSprint13TableSeeder::class);
        $this->call(FuncionarioUpdateTableSeeder::class);
        $this->call(TicketsCategoriaTableSeeder::class);

        //sprint-015
        $this->call(MenusSprint015TableSeeder::class);
        $this->call(AcessoSprint015TableSeeder::class);
        $this->call(AcessoPerfilSprint015TableSeeder::class);
        $this->call(PermissaoTicketSprint015TableSeeder::class);
        $this->call(ConfiguracoesTicketsSprint015::class);
        $this->call(TicketsAcaoTableSeeder::class);
        $this->call(TicketsGatilhoTableSeeder::class);
        $this->call(AlteracoesPermissoesSprint015TableSeeder::class);
        
        //sprint-016
        $this->call(PermissaoTicketSprint016TableSeeder::class);
        $this->call(MenusSprint016TableSeeder::class);
        $this->call(PermissaoMenuTicketSprint016TableSeeder::class);
        $this->call(IconeSprint016TableSeeder::class);       
        $this->call(FuncionariosAvatarsTableSeeder::class);

        //sprint-018
        $this->call(MenusSprint018TableSeeder::class);
        $this->call(PermissaoBaseDeConhecimentoSprint018TableSeeder::class);
        $this->call(AcessoPerfilSprint018TableSeeder::class);
        $this->call(PublicacoesCategoriasSprint018TableSeeder::class);
        $this->call(PublicacoesConteudoTipoSprint018TableSeeder::class);
        $this->call(FuncionarioSprint018TableSeeder::class);

        //sprint-022
        $this->call(PermissaoExportacaoPublicacaoSprint022TableSeeder::class);
        $this->call(PermissaoExportacaoPesquisaSprint022TableSeeder::class);
        $this->call(ParametroBaseDeConhecimentoSprint022TableSeeder::class);
        $this->call(MenusSprint022TableSeeder::class);
        $this->call(PermissaoBaseDeConhecimentoSprint022TableSeeder::class);
        $this->call(AcessoPerfilSprint022TableSeeder::class);
        $this->call(PermissaoPublicacoesHistoricoSprint022TableSeeder::class);        
    }
} 
