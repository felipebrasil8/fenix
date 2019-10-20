<?php

namespace App\Http\Middleware;

use Closure;
use App\Regras\ValidaDiaLoginRegra;
use App\Regras\ValidaHorarioLoginRegra;
use App\Regras\ValidaRedeLoginRegra;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class ValidacaoDeLogin
{
    use AuthenticatesUsers;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if ( auth()->check() ){
            
            $perfil_id = auth()->user()->perfil_id;
            $validaDia = new ValidaDiaLoginRegra($request, $perfil_id);            
            $validaHorario = new ValidaHorarioLoginRegra($request, $perfil_id);
            $validaRede = new ValidaRedeLoginRegra($request, $perfil_id);
            
            if( $validaDia->valida() || $validaHorario->valida() || $validaRede->valida() ){
                
                $this->guard()->logout();
                $request->session()->invalidate();
                return redirect('/login');
            }
        }

        return $next($request);
        
    }
}
