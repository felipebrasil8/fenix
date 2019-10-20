<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\ImagemController;
use Illuminate\Http\Request;
use App\Models\RH\FuncionarioAvatar;

class FuncionarioAvatarController extends Controller
{   
    private $funcionarioAvatar;

    private $entidade = 'Historico de avatar';

    public function __construct()
    {
        $this->funcionarioAvatar = new FuncionarioAvatar();
    }

    public function getAvatarGrande( $id )
    {
        if ( !empty(\Auth::user())){

            $base64 = $this->funcionarioAvatar->getAvatarGrande( $id );
            
            if ( !empty($base64) ) {
                return ImagemController::getResponse($base64);
            }
            return null;
            
        }
        abort(401, 'Usuário não logado.');

    }     

    public function getAvatarPequeno( $id )
    {
        if ( !empty(\Auth::user())){           
            
            $base64 = $this->funcionarioAvatar->getAvatarPequeno( $id );
                        
            if ( !empty($base64) ) {
                return ImagemController::getResponse($base64);
            }
            return null;
            
        }
        abort(401, 'Usuário não logado.');

    }

    public function destroy($id){
        
        $this->autorizacao( 'RH_FUNCIONARIO_EXCLUIR_HISTORICO_AVATAR' );

        try{

            FuncionarioAvatar::find($id)->delete();
            
            return \Response::json(['mensagem' => 'Histórico de avatar excluído com sucesso' ] ,200);            

        }catch(\Exception $e){

            return \Response::json(['errors' => ['Não foi possível excluir histórico de avatar'] ] ,404);
            
        }
    }

}

