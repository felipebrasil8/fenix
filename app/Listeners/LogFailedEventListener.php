<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Core\Login as LoginModel;
use App\Models\Configuracao\Perfil;
use App\Models\Configuracao\Usuario;

class LogFailedEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $login = new LoginModel;
        $usuario = new Usuario;

        if( isset($event->credentials['regra']) ){
            $usuarioLogin = $usuario->GetUsuarioPerfil($event->credentials['usuario']);

            $regra = $event->credentials['regra'];
            unset($event->credentials['regra']); 

            if($regra == 'dia'){
                $login->mensagem = 'DIA DA SEMANA E/OU HORÁRIO DE ACESSO NÃO PERMITIDO(S)';

            }else{
                $login->mensagem = 'REDE DE ACESSO NÃO PERMITIDA';
                
            }

            $login->usuario = $usuarioLogin->nome;
            $login->perfil = $usuarioLogin->perfil;

        }else{
            $usuario_ativo = $usuario->usuarioAtivo( $event->credentials['usuario'] );
    
            if( count($usuario_ativo) == 0 || $usuario_ativo[0]->ativo )
            {
                $login->mensagem = 'LOGIN SEM SUCESSO';
            }
            else
            {
                $login->mensagem = 'USUÁRIO INATIVO';
            }
    
            $login->usuario = 'DESCONHECIDO';
            $login->perfil = 'DESCONHECIDO';
        }



        $login->credencial = json_encode($event->credentials);
        $login->ip = \Request::ip();
        $login->tipo = 'FALHA';
        $login->save();
    }
}
