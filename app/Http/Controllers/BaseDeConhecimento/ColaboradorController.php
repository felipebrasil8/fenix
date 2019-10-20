<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\BaseDeConhecimento\PublicacaoColaborador;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;


class ColaboradorController extends Controller
{

    private $publicacaoColaborador;
    private $errors;
    private $success;
    private $entidade = 'Colaborador';
   
    public function __construct( Errors $errors,
                                    Success $success,
                                        PublicacaoColaborador $publicacaoColaborador   )
    {
    	 
        $this->publicacaoColaborador = $publicacaoColaborador;
        $this->errors = $errors;
        $this->success = $success;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );
        
       	$this->destroy($request->publicacao_id, $request->rascunho);

        $dados = array();
       
        foreach ($request->colaboradores as $value)
        {
        	if (isset($value))
        	{
        		array_push($dados, 
	            [
	                'usuario_inclusao_id' => \Auth::user()->id,
	                'funcionario_id' => trim($value),
	                'publicacao_id' => $request->publicacao_id,
                    'rascunho' => $request->rascunho
	            ]);	
        	}
        }

        $this->publicacaoColaborador->insert($dados);

        return [
            'mensagem' => $this->success->msgStore( $this->entidade.'(es)' ), 
            'id' => $request->publicacao_id
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $rascunho)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );
        
        $this->publicacaoColaborador->where('publicacao_id', '=', $id)->where('rascunho', '=', $rascunho)->delete();
    }

}
