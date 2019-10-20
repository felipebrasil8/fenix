<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cookie;

use App\Http\Controllers\Controller;
use App\Models\RH\Funcionario;
use App\Models\Core\PermissaoUsuario;
use App\Models\Ticket\TicketView;
use App\Models\Ticket\Ticket;
use App\Models\Configuracao\Usuario;
use Carbon\Carbon;
class HomeController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->ticketView = new TicketView();   
        $this->ticket = new Ticket();   
        $this->usuario = new Usuario();   
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        parent::setIdentificadorMigalha( 'HOME' );
        $funcionario = new Funcionario;

        $meusTicketsAbertos = $this->ticket->MeusTicketsAbertos( \Auth()->user()->id );

        return view('home.home', [
            'funcionarios' => $funcionario->proximosAniversarios(),
            'meusTicketsAbertos' => $this->ticket->MeusTicketsAbertos( \Auth()->user()->id ),
            'migalhas' => $this->migalhaDePao()
        ]);
    }

    public function proximosAniversarios()
    {
        $funcionario = new Funcionario;
        return $funcionario->proximosAniversarios();
    }

    /**
     * Call trait AuthenticatesUsers
     * @return Illuminate\Foundation\Auth\AuthenticatesUsers
     */
    public function logoutUser(Request $request)
    {
        return $this->logout($request);   
    }

    /**
     * Este metodo faz a conta pra mostrar a dta referente ( ex:se for ate 59 minutos, se for mais de uma hora em horas )
     */
    private function dataHistorico( $created_at ){        
        
        $date1 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create($created_at), 'd/m/Y H:i:s'));
        $date2 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create(Carbon::now('America/Sao_Paulo')), 'd/m/Y H:i:s'));

        if( $date2->diffInSeconds($date1) <= 59 )
        {
            $valor = $date2->diffInSeconds($date1).' segundos';
        }
        else if( $date2->diffInMinutes($date1) <= 1 )
        {
            $valor = $date2->diffInMinutes($date1).' min';
        }
        else if( $date2->diffInMinutes($date1) <= 59 )
        {
            $valor = $date2->diffInMinutes($date1).' mins';
        }
        else if( $date2->diffInHours($date1) == 1 )
        {
            $valor = '1 hora';   
        }
        else if( $date2->diffInHours($date1) <= 23 )
        {
            $valor = $date2->diffInHours($date1).' horas';   
        }
        else if( $date2->diffInDays($date1) == 1 )
        {
            $valor = '1 dia';   
        }
        else
        {
            $valor = $date2->diffInDays($date1).' dias';  
        }

        return $valor;        

    }



}
