<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BaseDeConhecimento\PublicacaoFavoritos;
use App\Models\BaseDeConhecimento\PublicacaoCategoria;
use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\BaseDeConhecimento\DadosPublicacaoView;
use App\Models\BaseDeConhecimento\HistoricoPesquisaPublicacaoView;

use App\Http\Controllers\Core\ExcelController;
use Excel;
use App\Models\Core\Icone;

class PublicacaoFavoritosController extends Controller
{
    private $publicacaoCategoria;
    private $publicacaoFavoritos;
    private $publicacao;

    public function __construct( PublicacaoCategoria $publicacaoCategoria, 
                                    PublicacaoFavoritos $publicacaoFavoritos,
                                        Publicacao $publicacao )
    {
        $this->publicacaoCategoria = $publicacaoCategoria;        
        $this->publicacaoFavoritos = $publicacaoFavoritos;
        $this->publicacao = $publicacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );        

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();

        return view('base-de-conhecimento.index', [
            'categoria_id' => 0,
            'migalhas' => $this->migalhaDePao('false'),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'can_categoria' => json_encode($this->publicacao->getPermissaoCategoria()),
            'favoritos' => true,
            'icones' => $icones
        ]);        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        if( $this->verificaSeJaExisteFavorito( $request->publicacao_id ) ){
            return \Response::json( [] ,400);
        }

        $fav = new PublicacaoFavoritos();

        $fav->usuario_id = $this->getUsuario();
        $fav->publicacao_id = $request->publicacao_id;
        $fav->save();

        return \Response::json( [] ,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($publicacao_id)
    {   

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        if( !($this->verificaSeJaExisteFavorito( $publicacao_id )) ){
            return \Response::json( [] ,400);
        }

        $fav = PublicacaoFavoritos::where('publicacao_id', '=', $publicacao_id )->where('usuario_id', '=', $this->getUsuario())->delete();        
        return \Response::json( [] ,200);

    }

    public function getUsuario(){
        return \auth()->user()->id; 
    }

    /**
     * [getPermissaoHabilitarEdicao - O botão (Habilitar edicao, Finalizar edicao)deve aparecer caso o usuário possua alguma das permissões de edição ]
     * @return [bool]
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

    public function showAll(  ){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );        
       
        return \Response::json( [
            'publicacoes' => $this->publicacao->getFavoritos(  ),
            'migalhas'    => $this->migalhaDePao('false'),            
            'can'         => $this->getPermissaoHabilitarEdicao()
        ] , 200);
    }

    private function verificaSeJaExisteFavorito( $id ){        

        $fav = PublicacaoFavoritos::where('publicacao_id', '=', $id )->where('usuario_id', '=', $this->getUsuario())->get();

        if( count( $fav ) > 0 )
            return true;
        
        return false;
    }    
}
