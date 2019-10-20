<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseDeConhecimento\PublicacaoHistorico;
use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\BaseDeConhecimento\PublicacaoColaborador;
use App\Models\BaseDeConhecimento\PublicacaoCategoria;
use App\Models\RH\Cargos;
use App\Models\BaseDeConhecimento\PublicacaoCargo;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Models\Core\Icone;
use App\Models\RH\Funcionario;

use App\Http\Controllers\BaseDeConhecimento\PublicacaoController;

class PublicacaoHistoricoController extends PublicacaoController
{

    private $publicacao_historico;
    private $publicacao;
    private $funcionario;
    private $publicacaoColaborador;
    private $publicacaoCategoria;
    private $cargo;
    private $publicacaoCargo;
    private $errors;
    private $success;

    private $entidade = 'Publicação Historico';    

    public function __construct( Errors $errors,
                                    Success $success )
    {
        $this->publicacao_historico = new PublicacaoHistorico;
        $this->publicacao = new Publicacao;
        $this->funcionario = new Funcionario;
        $this->publicacaoColaborador = new PublicacaoColaborador;
        $this->publicacaoCategoria =  new PublicacaoCategoria;
        $this->cargo = new Cargos;
        $this->publicacaoCargo = new PublicacaoCargo;
        $this->errors = $errors;
        $this->success = $success;
    }
    
    public function show( $publicacao_id ) {

        $this->autorizacao( 'BASE_PUBLICACOES_HISTORICO' );

        $publicacao = $this->publicacao->getCategoriaId($publicacao_id);
        
        if( empty($publicacao) )
        {
            return $this->naoEncontrado();
        }

        if( !$this->publicacao->getModoEdicaoAtivos() ){
            return redirect('base-de-conhecimento/publicacoes/'.$publicacao_id );
        }

         $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();

        return view('base-de-conhecimento.historico', [
            'can' => $this->getPermissaoHabilitarEdicao(),
            'migalhas' => $this->migalhaDePao('false'),
            'can_categoria' => json_encode($this->publicacao->getPermissaoCategoria()),
            'publicacao_id' => $publicacao_id,
            'categoria_id' => 0, // RETORNAR A CATEGORIA DA PUBLICACAO
            'publicacao' => $this->publicacao->getPublicacao($publicacao_id),
            'datas' => $this->publicacao->getPublicacaoDatas($publicacao_id),
            'funcionarios' => $this->funcionario->getFuncionariosAtivos(),
            'colaboradores' => $this->publicacaoColaborador->getColaboradorPublicacao($publicacao_id),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'cargos' => $this->cargo->getCargos(),
            'publicacao_cargos' => $this->publicacaoCargo::where('publicacao_id', $publicacao_id)->get(),
            'categoria_id' => $publicacao->publicacao_categoria_id, // RETORNAR A CATEGORIA DA PUBLICACAO
            'icones' => $icones
        ]);        
    }

    public function getDadosPublicacaoHistorico( $publicacao_id ){

        $this->autorizacao( 'BASE_PUBLICACOES_HISTORICO' );
        return [                
            'historicos' => $this->publicacao_historico->getPublicacaoHistorico( $publicacao_id ),
            'publicacao_id' => $publicacao_id                
        ];

    }

    public function destroy( $id ){
     
        $this->autorizacao( 'BASE_PUBLICACOES_HISTORICO_APAGAR' );
        

        PublicacaoHistorico::where('id', $id)
            ->update( 
            [
                'usuario_exclusao_id' => \Auth::user()->id,
            ]
        );

   
        $this->publicacao_historico->where('id', '=', $id)->delete();

        return [
            'mensagem' => $this->success->msgDestroy( $this->entidade )
        ];
    }

    protected function naoEncontrado()
    {
        return view( 'erros.naoEncontrado', [
            'titulo' => $this->entidade, 
            'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
            'mensagem' => $this->errors->mensagem( $this->entidade ), 
            'migalhas' => $this->migalhaDePao()
        ] );

    }




}
