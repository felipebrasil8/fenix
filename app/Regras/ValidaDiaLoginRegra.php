<?php

namespace App\Regras;

use App\Models\Configuracao\Perfil;
use App\Models\Configuracao\Usuario;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class ValidaDiaLoginRegra
{    
    
    use AuthenticatesUsers;

    /**
     * (seg,ter,qua,qui,sex, sab, dom) == true     
     * @var [bool]
     */
    public $todos_dias;
    public $perfil_id;
        
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
        $this->setDataInicial();        
    }

    public function setDataInicial( )
    {
        $this->todos_dias = Perfil::select('todos_dias')->where('id', $this->perfil_id)->first()->todos_dias;
    }
    
    public function valida(){

        $mytime = Carbon::now();        
        if( $this->todos_dias || ( !$this->todos_dias && $mytime->isWeekday() ) ){
            return false;
        }
                
        return true;
    }

}
