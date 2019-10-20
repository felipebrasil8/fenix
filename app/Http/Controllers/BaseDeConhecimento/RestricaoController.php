<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Models\BaseDeConhecimento\PublicacaoCargo;

class RestricaoController extends Controller
{

    private $publicacaoCargo;
    private $errors;
    private $success;
    private $entidade = 'Restrição de cargos';
   
    public function __construct( Errors $errors,
                                    Success $success,
                                        PublicacaoCargo $publicacaoCargo   )
    {
    	 
        $this->publicacaoCargo = $publicacaoCargo;
        $this->errors = $errors;
        $this->success = $success;
    }
    
    /**
     * Método que seta Cargos específicos para Publicação
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

       	$this->destroy($request->publicacao_id);

        $this->publicacaoCargo->insertPublicacaoCargo( $request );

        return [
            'mensagem' => $this->success->msgUpdate( $this->entidade ), 
            'id' => $request->publicacao_id
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        $this->publicacaoCargo->where('publicacao_id', '=', $id)->delete();
    }

}
