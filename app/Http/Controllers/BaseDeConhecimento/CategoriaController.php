<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\BaseDeConhecimento\PublicacaoCategoria;
use App\Http\Requests\BaseDeConhecimento\PublicacaoCategoriaRequest;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

class CategoriaController extends Controller
{
    private $publicacao;
    private $publicacaoCategoria;

    private $errors;
    private $success;
    private $entidade = 'Categoria';  
     
    public function __construct( PublicacaoCategoria $publicacaoCategoria,
                                    Publicacao $publicacao,
                                        Errors $errors,
                                            Success $success )
    {
        $this->publicacaoCategoria = $publicacaoCategoria;
        $this->publicacao = $publicacao;
        $this->errors = $errors;
        $this->success = $success;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );
        
        return $this->publicacaoCategoria->categoriaAtiva();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PublicacaoCategoriaRequest $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR' );

        $categoriaId = (isset($request->categoria_id) ? $request->categoria_id : NULL);

        $msg = $this->validaCategoriaPublicacoes($categoriaId);

        if( $msg != '' )
        {
            return \Response::json(['errors' => ['errors' => [$msg]]], 404);
        }

        $ordem = $this->validaOrdem($request->ordem, $categoriaId);

        $nome = $this->formatString()->strToUpperCustom($request->nome);
        $descricao = $this->formatString()->strToUpperCustom($request->descricao);
        $icone = $request->icone;

        $id = $this->publicacaoCategoria->insertGetId(
             [
                'usuario_inclusao_id' => \Auth::user()->id,
                'nome' => $nome,
                'descricao' => $descricao,
                'icone' => $icone,
                'ordem' => $ordem,
                'publicacao_categoria_id' => $categoriaId
             ]
        );
 
        return [
            'mensagem' => $this->success->msgStore( $this->entidade ), 
            'id' => $id  
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PublicacaoCategoriaRequest $request, $id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_CATEGORIA_EDITAR' );

        $categoriaId = (isset($request->categoria_id) ? $request->categoria_id : NULL);

        $msg = $this->validaCategoriaPublicacoes($categoriaId);

        if( $msg != '' )
        {
            return \Response::json(['errors' => ['errors' => [$msg]]], 404);
        }

        if( isset($categoriaId) && $categoriaId == $request->id )
        {
            return \Response::json(['errors' => ['errors' => ['Uma categoria não pode pertencer a ela mesma.']]], 404);
        }

        $ordem = $this->validaOrdem($request->ordem, $categoriaId);

        $nome = $this->formatString()->strToUpperCustom($request->nome);
        $descricao = $this->formatString()->strToUpperCustom($request->descricao);
        $icone = $request->icone;

        $this->publicacaoCategoria->where('id', $request->id)
            ->update( 
             [
                'usuario_alteracao_id' => \Auth::user()->id,
                'nome' => $nome,
                'descricao' => $descricao,
                'icone' => $icone,
                'ordem' => $ordem,
                'publicacao_categoria_id' => $categoriaId
             ]
        );

        return [
            'mensagem' => $this->success->msgUpdate( $this->entidade ), 
            'id' => $request->id  
        ];
    }

    private function validaCategoriaPublicacoes( $categoriaId )
    {
        if( isset($categoriaId) )
        {
            if( $this->publicacao->getPublicacoes($categoriaId)->count() > 0 )
            {
                return 'Não é possível cadastrar uma subcategoria em uma categoria que possua publicações.';
            }
        }

        return '';
    }

    private function validaOrdem( $ordem, $categoriaId )
    {
        if($ordem == 0 || \Auth::user()->can( 'BASE_PUBLICACOES_CATEGORIA_ORDENAR' ) == false)
        {
            if( isset($categoriaId) )
            {
                $newOrdem = $this->publicacaoCategoria->where('publicacao_categoria_id', $categoriaId)->max('ordem');
            }
            else
            {
                $newOrdem = $this->publicacaoCategoria->whereNull('publicacao_categoria_id')->max('ordem');
            }

            return $newOrdem+1;
        }

        return $ordem;
    }
}
