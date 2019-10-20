<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FenixRepositoryProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //Funcionario
        $this->app->bind( 
            \App\Repositories\RH\FuncionarioRepositoryInterface::class,
            \App\Repositories\RH\FuncionarioRepositoryEloquent::class);

        //PermissaoMenu
        $this->app->bind( 
            \App\Repositories\Core\PermissaoMenuRepositoryInterface::class,
            \App\Repositories\Core\PermissaoMenuRepositoryEloquent::class);

        //Permissao
        $this->app->bind( 
            \App\Repositories\Core\PermissaoRepositoryInterface::class,
            \App\Repositories\Core\PermissaoRepositoryEloquent::class);

        //Perfil
        $this->app->bind( 
            \App\Repositories\Configuracao\PerfilRepositoryInterface::class,
            \App\Repositories\Configuracao\PerfilRepositoryEloquent::class);

        //Acesso
        $this->app->bind( 
            \App\Repositories\Configuracao\AcessoRepositoryInterface::class,
            \App\Repositories\Configuracao\AcessoRepositoryEloquent::class);

        //AcessoPerfil
        $this->app->bind( 
            \App\Repositories\Configuracao\AcessoPerfilRepositoryInterface::class,
            \App\Repositories\Configuracao\AcessoPerfilRepositoryEloquent::class);

        //AcessoPermissao
        $this->app->bind( 
            \App\Repositories\Configuracao\AcessoPermissaoRepositoryInterface::class,
            \App\Repositories\Configuracao\AcessoPermissaoRepositoryEloquent::class);
        
        //Usuario
        $this->app->bind( 
            \App\Repositories\Configuracao\UsuarioRepositoryInterface::class,
            \App\Repositories\Configuracao\UsuarioRepositoryEloquent::class);

        //Parâmetros
        $this->app->bind( 
            \App\Repositories\Configuracao\Sistema\ParametroRepositoryInterface::class,
            \App\Repositories\Configuracao\Sistema\ParametroRepositoryEloquent::class);

        //Parâmetros Grupo
        $this->app->bind( 
            \App\Repositories\Configuracao\Sistema\ParametroGrupoRepositoryInterface::class,
            \App\Repositories\Configuracao\Sistema\ParametroGrupoRepositoryEloquent::class);

        //Parâmetros Tipo
        $this->app->bind( 
            \App\Repositories\Configuracao\Sistema\ParametroTipoRepositoryInterface::class,
            \App\Repositories\Configuracao\Sistema\ParametroTipoRepositoryEloquent::class);
        //ParametroView
        $this->app->bind( 
            \App\Repositories\Configuracao\Sistema\ParametroViewRepositoryInterface::class,
            \App\Repositories\Configuracao\Sistema\ParametroViewRepositoryEloquent::class);
        //UsuarioView
        $this->app->bind( 
            \App\Repositories\Configuracao\UsuarioViewRepositoryInterface::class,
            \App\Repositories\Configuracao\UsuarioViewRepositoryEloquent::class);
        //LogsLogin
        $this->app->bind( 
            \App\Repositories\Log\LogsLoginRepositoryInterface::class,
            \App\Repositories\Log\LogsLoginRepositoryEloquent::class);

        //ComplexidadeSenha
        $this->app->bind( 
            \App\Repositories\Configuracao\Sistema\PoliticaSenhaRepositoryInterface::class,
            \App\Repositories\Configuracao\Sistema\PoliticaSenhaRepositoryEloquent::class);

        // Areas
        $this->app->bind( 

            \App\Repositories\RH\AreaRepositoryInterface::class,
            \App\Repositories\RH\AreaRepositoryEloquent::class);

        // Departamentos
        $this->app->bind( 
            \App\Repositories\RH\CargosRepositoryInterface::class,
            \App\Repositories\RH\CargosRepositoryEloquent::class);

        // Departamentos
        $this->app->bind( 
            \App\Repositories\RH\DepartamentoRepositoryInterface::class,
            \App\Repositories\RH\DepartamentoRepositoryEloquent::class);


        // Departamentos
        $this->app->bind( 
            \App\Repositories\Core\NotificacaoRepositoryInterface::class,
            \App\Repositories\Core\NotificacaoRepositoryEloquent::class);

        // Tickes
        $this->app->bind( 
            \App\Repositories\Ticket\TicketRepositoryInterface::class,
            \App\Repositories\Ticket\TicketRepositoryEloquent::class);

        //TicketView
        $this->app->bind( 
            \App\Repositories\Ticket\TicketViewRepositoryInterface::class,
            \App\Repositories\Ticket\TicketViewRepositoryEloquent::class);

        // TicketsStatus
        $this->app->bind( 
            \App\Repositories\Configuracao\Ticket\StatusRepositoryInterface::class,
            \App\Repositories\Configuracao\Ticket\StatusRepositoryEloquent::class);

        // TicketsPrioridade
        $this->app->bind( 
            \App\Repositories\Configuracao\Ticket\PrioridadeRepositoryInterface::class,
            \App\Repositories\Configuracao\Ticket\PrioridadeRepositoryEloquent::class);

        // TicketsCampo
        $this->app->bind( 
            \App\Repositories\Configuracao\Ticket\CampoRepositoryInterface::class,
            \App\Repositories\Configuracao\Ticket\CampoRepositoryEloquent::class);

        // TicketsCategoria
        $this->app->bind( 
            \App\Repositories\Configuracao\Ticket\CategoriaRepositoryInterface::class,
            \App\Repositories\Configuracao\Ticket\CategoriaRepositoryEloquent::class);
    }
}
