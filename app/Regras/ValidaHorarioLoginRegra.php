<?php

namespace App\Regras;

use App\Models\Configuracao\Perfil;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use App\Models\Configuracao\Usuario;

class ValidaHorarioLoginRegra
{
    
    use RedirectsUsers, ThrottlesLogins;

    public  $perfil_id;
    private $tempo_atual;
    private $tempo_inicial;
    private $tempo_final;
    
    public function __construct(Request $request, $perfil_id)
    {
        if( is_null($perfil_id) ){            
            $perfil_id = Usuario::select('perfil_id', 'password')->where('usuario', $request->usuario)->first();
            
            if( !isset($perfil_id) || !\Hash::check( $request->password , $perfil_id->password) ){
                return true;
            }
            
            $perfil_id = $perfil_id->perfil_id;
        }
        
        $this->perfil_id = $perfil_id;
        $this->setTempoInicial();
        $this->setTempoFinal();
        $this->setSegundosTotalAtual();        
    }

    private function setTempoInicial( ){
        
        $horario = Perfil::select('horario_inicial')->where('id', $this->perfil_id)->first()->horario_inicial;
        $this->tempo_inicial = $this->calculaSegundos( $horario );
    }
    
    private function setTempoFinal( ){
        
        $horario = Perfil::select('horario_final')->where('id', $this->perfil_id)->first()->horario_final;
        $this->tempo_final = $this->calculaSegundos( $horario );
    }    
    
    private function setSegundosTotalAtual(){
        
        $mytime = Carbon::now();
        $this->tempo_atual = $this->calculaSegundos( $mytime->toTimeString() );        
    }
    
    public function valida(){

        //passou na regra
        if( $this->tempo_atual >= $this->tempo_inicial && $this->tempo_atual <= $this->tempo_final ){
            return false;
        }
        
        return true;
    }
    
    private function calculaSegundos( $time ){
        
        $segundos = 0;
        $tempo = explode(":", $time );
        $segundos = $tempo[0] * 60 * 60;
        $segundos += $tempo[1] * 60;
        $segundos += $tempo[2];

        return $segundos;
    }

}
