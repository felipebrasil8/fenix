<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Configuracao\Usuario;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cookie;
use App\Regras\ValidaDiaLoginRegra;
use App\Regras\ValidaHorarioLoginRegra;
use App\Regras\ValidaRedeLoginRegra;
use App\Models\Core\Login as LoginModel;
use Illuminate\Auth\Events\Failed;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        // Limpar todos os coockies
        foreach (request()->cookie() as $key => $value) {
            Cookie::queue(Cookie::forget($key));
        }
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $url = str_replace($request->root(), '', $request->session()->previousUrl());

        if( $url != '/login' )
        {
            $this->redirectTo = $url;
        }
        
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        
        $validaDia = new ValidaDiaLoginRegra($request, null);
        $validaHorario = new ValidaHorarioLoginRegra($request, null);        
        $validaRede = new ValidaRedeLoginRegra($request, null);        
        
        $regraDia = $validaDia->valida();
        $regraHorario = $validaHorario->valida();
        $regraRede = $validaRede->valida();

        if( $regraDia || $regraHorario || $regraRede ){
            
            $user = '';
            
            if ( $regraHorario || $regraDia ){
                $credentials = ['usuario'=>$request->usuario, 'password' => '', 'regra' => 'dia'];
                $msg = "Data e/ou horário de acesso não permitido(s).";
            }
            if( $regraRede ){
                $credentials = ['usuario'=>$request->usuario, 'password' => '', 'regra' => 'rede'];
                $msg = "Rede de acesso não permitida";
                
            }
            
            event(new Failed($user, $credentials));
            
            $request->session()->invalidate();            
            return redirect('/login')->withErrors([$msg]);
        }
        
        if ($this->attemptLogin($request))
        {
            if( \Auth()->user()->senha_alterada == false )
            {
                Usuario::whereId( \Auth()->user()->id )->update( ['visualizado_senha_alterada' => false ] );
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * metodo sobrescrito do trait AuthenticatesUsers
     */

    public function username()
    {
        return 'usuario';
    }

}
