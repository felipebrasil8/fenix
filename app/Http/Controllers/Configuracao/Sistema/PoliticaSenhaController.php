<?php

namespace App\Http\Controllers\Configuracao\Sistema;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Configuracao\Sistema\PoliticaSenha;
use App\Http\Requests\Configuracao\Sistema\PoliticaSenhaRequest;

class PoliticaSenhaController extends Controller
{
   	private $entidade = 'Política de senha';
    private $politicaSenha;

    public function __construct( 
                                PoliticaSenha $politicaSenha
                            )
    {
        $this->politicaSenha = $politicaSenha;
    }

    public function index()
    {
        $this->autorizacao( 'CONFIGURACAO_SISTEMA_POLITICA_VISUALIZAR' );
       
        $politica = $this->politicaSenha->select('id','tamanho_minimo', 'qtde_minima_letras', 'qtde_minima_numeros', 'qtde_minima_especial', 'caractere_especial','maiusculo_minusculo')->where('ativo', '=', true)->first();

        return view('configuracao.sistema.politica_senhas.index', [
            'politica'   => $politica,
            'migalhas' => $this->migalhaDePao( 'INDEX' ) ]
        );
    }

    
    public function update( PoliticaSenhaRequest $request, $id ){//ok

        $this->autorizacao('CONFIGURACAO_ACESSO_EDITAR');

        $politica = $this->politicaSenha->where('id', $id)->first();
        
        if( !isset($politica) ){
            return \Response::json(['errors' => ['errors' => [ $this->errors()->msgUpdate( $this->entidade ) ] ] ] ,404);
        }


        if( $this->validarAlteracao($request, $id) )
        {
            \DB::beginTransaction();
            try
            {
                
                $this->politicaSenha->where('id', '=', $id)->update([
                    'ativo' => false,
                    'usuario_exclusao_id' => \Auth::user()->id,
                    'dt_exclusao' => date("d-m-Y H:i:s")
                ]);

                $politicaSenha = $this->politicaSenha->insertGetId([
                    'tamanho_minimo' => $request->tamanho_minimo,
                    'qtde_minima_letras' => $request->qtde_minima_letras,
                    'qtde_minima_numeros' => $request->qtde_minima_numeros,
                    'qtde_minima_especial' => $request->qtde_minima_especial,
                    'caractere_especial' => $request->caractere_especial,
                    'maiusculo_minusculo' => $request->maiusculo_minusculo,
                    'usuario_inclusao_id' => \Auth::user()->id,
                    
                ]);
                
                \DB::commit();
            }
            catch(\Exception $e)
            {
                \DB::rollback();
                return \Response::json(['errors' => ['errors' => [ $this->errors()->msgUpdate( $this->entidade ) ] ] ] ,404);
            }
            return ['mensagem' => $this->success()->msgStore( $this->entidade ), 'id' => $politicaSenha];  
        }
        else
        {
            return \Response::json(['errors' => ['errors' => [ $this->errors()->msgStore( $this->entidade ).', não houve alterações' ] ] ] ,404);
        }
    }

    private function validarAlteracao( $request, $id )
    {
   
        $politica = $this->politicaSenha->select('id','tamanho_minimo', 'qtde_minima_letras', 'qtde_minima_numeros', 'qtde_minima_especial', 'caractere_especial','maiusculo_minusculo')->where('ativo', '=', true)->first();
        
        /*
         * Seta um novo objeto com os atributos do request
         */
        $senhaRequest = new PoliticaSenha;
        $senhaRequest->id = $request->id;
        $senhaRequest->tamanho_minimo = $request->tamanho_minimo;
        $senhaRequest->qtde_minima_letras = $request->qtde_minima_letras;
        $senhaRequest->qtde_minima_numeros = $request->qtde_minima_numeros;
        $senhaRequest->qtde_minima_especial = $request->qtde_minima_especial;
        $senhaRequest->caractere_especial = $request->caractere_especial;
        $senhaRequest->maiusculo_minusculo = $request->maiusculo_minusculo;

       
        if($politica->getAttributes() == $senhaRequest->getAttributes())
        {
            return false;
        }
        else
        {
            return true;
        }            
     
    }





}
