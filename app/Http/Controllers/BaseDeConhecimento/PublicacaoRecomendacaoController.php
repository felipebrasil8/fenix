<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BaseDeConhecimento\PublicacoesRecomendacoes;
use Event;
use App\Events\NotificacaoEvent;
use Illuminate\Support\Str;
use App\Models\BaseDeConhecimento\PublicacaoCargo;
use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\RH\Funcionario;
use Validator;

class PublicacaoRecomendacaoController extends Controller
{

	public function store( Request $request ){


		$validator = Validator::make($request->recomendacao, [
            'mensagem' => 'required|max:100',
            'funcionario' => 'required'            
        ]);        

        $messsages = array(
            'funcionario.required'=>'O campo funcionário e obrigatório.',
            'mensagem.required'=>'o campo mensagem é obrigatório.',
            'mensagem.max'=>'O campo mensagem deve conter 100 caracteres no máximo.'
        );

        $rules = array(            
            'mensagem' => 'required|max:100',
            'funcionario' => 'required'            
        );        

        $validator = Validator::make($request->recomendacao, $rules, $messsages);

        if ($validator->fails()) {

        	return \Response::json( ['errors' => $validator->messages() ] ,404);

        }


        try{

			$this->validaRecomendacao( $request->recomendacao );

			$recomendacao = new PublicacoesRecomendacoes();

			$recomendacaoId = $recomendacao->setRecomendacao( $request->recomendacao );

        	if( isset( $recomendacaoId ) ) {

		        event(
		            new NotificacaoEvent( $recomendacaoId, ['notificacao' => $this->getNotificacao( $recomendacaoId, 
		            	$request->recomendacao['funcionario_recomendado_id'], 
		            	$request->recomendacao['publicacao_id'],
		            	'Nova recomendação!!!' ) ] ) 
		        );
		    }

		}catch( \Exception $e ){

			return \Response::json( ['errors' => ['errors' => [ $e->getMessage() ]]] ,404);
		}

		return [
            'mensagem' => 'Recomendação enviada com sucesso.', 
            'id' => $request->recomendacao['publicacao_id']
        ];
    }

    /**
     * getNotificacao retorna os dados para gerar a notificação
     * @param  integer $id_mensagem ID da mensagem gerada por um funcinário para uma publicação
     * @return array                dados gerar a notificação
     */
    public function getNotificacao( $id_mensagem, $funcionarioRecomendadoId, $publicacaoId, $mensagem )
    {
        return [
			'titulo'              => 'Base de conhecimento',
			'mensagem'            => $mensagem,
			'modulo'              => 'base_de_conhecimento',
			'url'  				  => '/base-de-conhecimento/publicacoes/'.$publicacaoId,
			'icone'               => 'share-alt',
			'cor'                 => '#f39c12',
			'usuario' => $this->formataIdFuncionario( $funcionarioRecomendadoId )
        ];
    }

    private function formataIdFuncionario( $funcionarioId )
    {
        $dados = [];        
        array_push($dados, ['id' => $funcionarioId]);

        return $dados;
    }


    private function validaRecomendacao( $recomendacao ){


    	if( !isset( $recomendacao['publicacao_id'] ) || !isset( $recomendacao['funcionario_recomendado_id'] ) ){
    		throw new \Exception("Por favor selecione um funcionário.", 1);    		
    	}

    	$funcNome = Funcionario::where('nome', $recomendacao['funcionario'])->where('ativo', true)->where('id', $recomendacao['funcionario_recomendado_id'])->first();
    	if(is_null( $funcNome )){    		
    		throw new \Exception("Por favor selecione um funcionário válido.", 1);    		
    	}

    	$restricaoAcessoPublicacao = Publicacao::select( 'restricao_acesso' )->where('id', $recomendacao['publicacao_id'])->first();
        if( $restricaoAcessoPublicacao['restricao_acesso'] ) {

        	$func = new Funcionario();
        	$regra = $func->validaFuncionariosComPermissaoNaPublicacao( $recomendacao['publicacao_id'], $recomendacao['funcionario_recomendado_id'] );

        	if( !$regra ){
        		throw new \Exception("Não é possível recomendar esta publicação. \nVerifique se funcionário possui permissão.", 1);
            }            
        }

        return true;
    }



    public function recomendacaoVisualizada( Request $request, $id )
    {
        $recomendacao = new PublicacoesRecomendacoes();
        $recomendacao->setVisualizada($id);
    }



    public function showRecomendados( $publicacaoId ){

    	$recomendacao = new PublicacoesRecomendacoes();
    	return $recomendacao->getRecomendadosPorPublicacao( $publicacaoId );
    }
}
		

