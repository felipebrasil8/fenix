<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Core\Login as LoginModel;
use App\Models\Configuracao\Perfil;
use Illuminate\Http\Request;

class LogLoginEventListener
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {

        $api = '';
        if( !empty(\Auth::guard('api')->user()) )
        {
            $api = ' (TOKEN)';
        }

        $login = new LoginModel;

        $perfil = Perfil::find( \Auth::user()->perfil_id );

        $login->usuario_id = $event->user->id;
        $login->usuario = \Auth::user()->nome;

        $login->perfil_id = \Auth::user()->perfil_id;
        $login->perfil = $perfil->nome;

        $arr = array(
            "usuario"  => $event->user->usuario,
            "password" => $event->user->password
        );

        $login->credencial = json_encode( $arr );

        $login->ip = \Request::ip();
        $login->tipo = 'LOGIN';
        $login->mensagem = 'LOGADO COM SUCESSO'.$api;
        $login->save();

    }
}
