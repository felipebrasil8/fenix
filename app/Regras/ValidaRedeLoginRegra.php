<?php

namespace App\Regras;

use App\Models\Configuracao\Perfil;
use App\Models\Configuracao\Usuario;
use App\Util\ValidateIpAddress;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class ValidaRedeLoginRegra
{    
    
    use AuthenticatesUsers;

    /**
     * (seg,ter,qua,qui,sex, sab, dom) == true     
     * @var [bool]
     */
    public $configuracao_de_rede;
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
        $this->setRedePermitida();        
    }

    public function setRedePermitida( ){

        $rede = Perfil::select('configuracao_de_rede')->where('id', $this->perfil_id)->first();        
        $this->configuracao_de_rede = $rede->configuracao_de_rede;
    }
    
    public function valida(){

        $validaIpAddress = new ValidateIpAddress();
        
        if( !is_null($this->configuracao_de_rede) ){
            if( $validaIpAddress->VericaRede( $this->configuracao_de_rede) ){
                return true;
            }
        }

        return false;
    }

}
