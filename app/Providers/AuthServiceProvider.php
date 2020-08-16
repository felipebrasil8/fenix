<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Core\PermissaoUsuario;
use App\Models\Core\Permissao;

use App\Policies\TicketPolicy;
use App\Models\Ticket\Ticket;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    Ticket::class => TicketPolicy::class,
  ];

  /**
   * Register any authentication / authorization services.
   * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
   * @return void
   */
  public function boot(GateContract $gate)
  {
    $this->registerPolicies($gate);
    
    $permissoes = Permissao::select('id', 'identificador')->get();
    foreach ($permissoes as $permissao)
    {
      /**
       * Para o can() da Controller
       * @param string $ability
       * @param callable|string $callback
       * O $callback ou a function($usuario, $object é executada somente na chamada do gate
       *    Caso exita, o primeiro parâmetro é o usuáio logado (por padrão).
       *    Do primeiro parâmetro em diante, o parâmetro é passado na chamada do gate
       * @return bool
       */
      $gate::define($permissao->identificador, function ( $usuario )
      {
        $permissaoUsuario = new PermissaoUsuario();
        return $permissaoUsuario->permissaoIdentificador( $usuario, $usuario->identificador );
      });
    }

    /**
     * Para o @can() do blade.
     */
    $gate::before( function ( $usuario, $identificador )
    {
      $permissaoUsuario = new PermissaoUsuario();
      return $permissaoUsuario->permissaoIdentificador( $usuario, $identificador );
    } );
  }
}
