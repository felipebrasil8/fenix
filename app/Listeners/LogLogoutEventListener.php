<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Core\Login as LoginModel;
use App\Models\Configuracao\Perfil;
use Illuminate\Http\Request;

class LogLogoutEventListener
{
    public $request;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $login = new LoginModel;

        $perfil = Perfil::find( \Auth::user()->perfil_id );

        $login->usuario_id = $event->user->id;
        $login->usuario = \Auth::user()->nome;

        $login->perfil_id = \Auth::user()->perfil_id;
        $login->perfil = $perfil->nome;

        $arr = array(
            "usuario"  => $event->user->usuario,
            "password" => null
        );

        $login->credencial = json_encode( $arr );
        $login->ip = \Request::ip();
        $login->tipo = 'LOGOUT';
        $login->mensagem = 'DESLOGADO COM SUCESSO';
        $login->save();            
       
    }
}
