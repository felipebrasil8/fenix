<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\BaseDeConhecimento\PublicacaoCategoria;
use App\Models\Core\Icone;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Util\Date;
use Carbon\Carbon;


class PublicacaoProximasPublicacoesController extends Controller
{
    private $publicacao;
    private $publicacaoCategoria;

    private $errors;
    private $success;
    private $entidade = 'Proximas publicações';    

    public function __construct( Errors $errors,
                                    Success $success,
                                        Publicacao $publicacao,
                                            PublicacaoCategoria $publicacaoCategoria )
    {
        $this->publicacaoCategoria = $publicacaoCategoria;
        $this->publicacao = $publicacao;
        $this->errors = $errors;
        $this->success = $success;
    }

    public function index()
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        if( !$this->publicacao->getModoEdicaoAtivos() )
            return redirect('base-de-conhecimento/publicacoes/');

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();

        return view('base-de-conhecimento.index', [
            'categoria_id' => 0,
            'migalhas' => $this->migalhaDePao('false'),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'can_categoria' => json_encode($this->publicacao->getPermissaoCategoria()),
            'proximas_publicacoes' => true,
            'icones' => $icones
        ]);    
    }

    public function showAll()
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );        
       
        $publicacoes = $this->publicacao->getProximasPublicacoes();

        return \Response::json( [
            'publicacoes' => $this->publicacao->formataProximasPublicacoes($publicacoes, 'proximas'),           
            'publicacoes_count' => $publicacoes->count(),
            'can'         => $this->getPermissaoHabilitarEdicao()
        ] , 200);
    }

    /**
     * getPermissaoHabilitarEdicao - O botão (Habilitar edicao, Finalizar edicao) deve aparecer caso o usuário possua alguma das permissões de edição
     * @return bool
     */
    protected function getPermissaoHabilitarEdicao(){
        
        $cadastrarPublicacao = \Auth::user()->can( 'BASE_PUBLICACOES_CADASTRAR' );
        $editarPublicacao    = \Auth::user()->can( 'BASE_PUBLICACOES_EDITAR' );
        $editarConteudo      = \Auth::user()->can( 'BASE_PUBLICACOES_CONTEUDO_EDITAR' );

        if( $cadastrarPublicacao || $editarPublicacao || $editarConteudo ){
            return true;
        }

        return false;
    }
}
