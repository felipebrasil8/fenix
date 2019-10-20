<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Requests\BaseDeConhecimento\PublicacaoRequest;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\BaseDeConhecimento\PublicacaoCategoria;
use App\Models\BaseDeConhecimento\PublicacaoColaborador;
use App\Models\BaseDeConhecimento\PublicacaoHistorico;
use App\Models\BaseDeConhecimento\PublicacaoVisualizacao;
use App\Models\BaseDeConhecimento\PublicacaoCargo;
use App\Models\BaseDeConhecimento\PublicacaoTag;
use App\Models\BaseDeConhecimento\PublicacaoConteudo;
use App\Models\BaseDeConhecimento\PublicacoesRecomendacoes;
use App\Models\RH\Funcionario;
use App\Models\RH\Cargos;
use App\Http\Controllers\Core\ImagemController;
use Illuminate\Support\Str;
use App\Models\Configuracao\Usuario;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\Core\Icone;

class PublicacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    private $publicacaoCategoria;
    private $publicacaoColaborador;
    private $publicacaoHistorico;
    private $publicacaoVisualizacao;
    private $publicacaoCargo;
    private $publicacao;
    private $publicacaoTag;
    private $publicacaoConteudo;
    private $publicacaoRecomendacao;


    private $errors;
    private $success;
    private $entidade = 'Publicação';    

    public function __construct( Errors $errors,
                                    Success $success,
                                        PublicacaoCategoria $publicacaoCategoria,
                                            Publicacao $publicacao,
                                                PublicacaoColaborador $publicacaoColaborador,
                                                    PublicacaoHistorico $publicacaoHistorico,
                                                        PublicacaoVisualizacao $publicacaoVisualizacao,
                                                            Funcionario $funcionario,
                                                                Cargos $cargo, 
                                                                    PublicacaoCargo $publicacaoCargo,
                                                                        PublicacaoTag $publicacaoTag,
                                                                            PublicacaoConteudo $publicacaoConteudo,
                                                                                PublicacoesRecomendacoes $publicacaoRecomendacao )
    {

        $this->publicacaoCategoria = $publicacaoCategoria;
        $this->publicacao = $publicacao;
        $this->publicacaoColaborador = $publicacaoColaborador;
        $this->publicacaoHistorico = $publicacaoHistorico;
        $this->publicacaoVisualizacao = $publicacaoVisualizacao;
        $this->publicacaoCargo = $publicacaoCargo;
        $this->funcionario = $funcionario;
        $this->publicacaoTag = $publicacaoTag;
        $this->publicacaoConteudo = $publicacaoConteudo;
        $this->publicacaoRecomendacao = $publicacaoRecomendacao;

        $this->cargo = $cargo;
        $this->errors = $errors;
        $this->success = $success;
    }

    public function index(Request $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );
       
        return redirect('/base-de-conhecimento/publicacoes/categoria/1');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PublicacaoRequest $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_CADASTRAR' );

        $id = $this->publicacao->insertGetId(
             [
                'usuario_inclusao_id' => \Auth::user()->id,
                'titulo' => $request->titulo,
                'resumo' => $request->resumo,
                'publicacao_categoria_id' => $request->categoria_id,
                'lista_relacionados' => $request->lista_relacionados,
             ]
        );

        $this->publicacaoHistorico->setHistoricoCricacaoPublicacao( $id );
 
        return [
            'mensagem' => $this->success->msgStore( $this->entidade ), 
            'id' => $id  
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $publicacao = $this->publicacao->getCategoriaId($id);
        
        if( empty($publicacao) )
        {
            return $this->naoEncontrado();
        }

        $this->validaCargo( $id );
        
        if( !$this->publicacao->getModoEdicaoAtivos() && $this->checaSeEstaPublicado( $id ) )
            return redirect('base-de-conhecimento/publicacoes/categoria/'.$publicacao->publicacao_categoria_id );
        
        if( !$this->publicacao->getModoEdicaoAtivos() ){
           $this->publicacaoVisualizacao->setVisualizacao($id, \Auth::user()->id);
        }

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();
       
        return view('base-de-conhecimento.visualizar', [
            'publicacao_id' => $id,
            'datas' => $this->publicacao->getPublicacaoDatas($id),
            'categoria_id' => $publicacao->publicacao_categoria_id,
            'publicacao' => $this->publicacao->getPublicacao($id), 
            'migalhas' => $this->migalhaDePao('false'),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'cargos' => $this->cargo->getCargos(),
            'publicacao_cargos' => $this->publicacaoCargo::where('publicacao_id', $id)->get(),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'can_categoria' => json_encode($this->publicacao->getPermissaoCategoria()),
            'icones' => $icones
        ]);
    }

    private function checaSeEstaPublicado( $idPublicacao ) {

        $publicacao = $this->publicacao->getPublicacaoDatas($idPublicacao);

        if( is_null( $publicacao->dt_publicacao ) ) {
            return true;
        }
        return false;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_CONTEUDO_EDITAR' );
        
        $param = ['request' => 'edit'];
        
        $colaboradores = $this->publicacaoColaborador->getColaboradorPublicacao($id);

        $param['colaboradores'] = $colaboradores->values();

        if( count($this->publicacaoColaborador->getColaboradorPublicacao($id, true)) > 0 ||
            count($this->publicacaoTag->getTagsPublicacao($id, true)) > 0 ||
            count($this->publicacaoConteudo->getConteudoPublicacao($id, true)) > 0 )
        {
            $param['existe_rascunho'] = 'true';
        }
        
        return $this->showEditConteudo($id, $param);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PublicacaoRequest $request, $id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        $this->validaCargo( $request->id );

        $this->publicacao->where('id', $request->id)
            ->update( 
             [
                'usuario_alteracao_id' => \Auth::user()->id,
                'titulo' => $request->titulo,
                'resumo' => $request->resumo,
                'publicacao_categoria_id' => $request->categoria_id,
                'lista_relacionados' => $request->lista_relacionados,
             ]
        );

        return [
            'mensagem' => $this->success->msgUpdate( $this->entidade ), 
            'id' => $request->id  
        ];
    }

    
    public function getPublicacoes($idCategoria){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );
        
        $categoria = $this->publicacaoCategoria->getCategoriaNome($idCategoria);

        if( empty($categoria) )
        {
            return $this->naoEncontrado();
        }
      
        if( $this->checaSeUnicaPublicacao( $idCategoria ) && !$this->publicacao->getModoEdicaoAtivos() ){

            return redirect('base-de-conhecimento/publicacoes/'.$this->getIdUnicaPublicacao( $idCategoria ) );
            exit;
        }

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();
     
        return view('base-de-conhecimento.index', [
            'categoria_id' => $idCategoria,
            'migalhas' => $this->migalhaDePao('false'),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'can_categoria' => json_encode($this->publicacao->getPermissaoCategoria()),
            'icones' => $icones
        ]);

    }


    /**
     * [checaSeUnicaPublicacao - Método checa se tem apenas uma publicação ativada ou desativada]
     * @param  [int] $id [id da categoria]
     * @return [bool]
     */
    private function checaSeUnicaPublicacao( $id )
    {
        $total = $this->publicacao->getPublicadas( $id )->count();
        return $total == 1 ? true : false ;
    }


    private function getIdUnicaPublicacao( $idCategoria )
    {
        return $this->publicacao->getPublicadas( $idCategoria )[0]->id;
    }

    public function getPublicacao($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $this->validaCargo( $id );

        $publicacao = $this->publicacao->getPublicacao( $id );

        return [ 
            'publicacao' => $publicacao,
            'datas' => $this->publicacao->getPublicacaoDatas($id),
            'visualizacoes' => $this->publicacaoVisualizacao->getVisualizacoesPublicacao($id),
            'relacionadas'  => $this->publicacao->getPublicacoesRelacionadas($publicacao),
            'recomendadas'  => $this->publicacaoRecomendacao->getRecomendacaoPublicacao($id),
            'can' => $this->getPermissaoUsuario()
        ];  
    }

    /**
     * [getPublicacoesCategoria - verifica cookie de editar publicacao para editar conforme regra de desativado ou ativado]
     * @param  [integer] $publicacao_categoria_id [id da categoria na publicação]
     * @return [Collecttion] $item                [description]
     */
    public function getPublicacoesCategoria( $publicacao_categoria_id, Request $request ){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );
        
        if( $this->publicacao->getModoEdicaoAtivos() ) {

            $publicacoes = [                
                'naoPublicadas' => ['dados' => $this->publicacao->getNaoPublicadas( $publicacao_categoria_id), 'titulo' => 'Não publicadas'],
                'publicadas' => ['dados' => $this->publicacao->getPublicadas( $publicacao_categoria_id), 'titulo' => 'Publicadas'],
                'desativadas' => ['dados' => $this->publicacao->getDesativadas( $publicacao_categoria_id ), 'titulo' => 'Desativadas']
            ];
            
        }else{//Ativada
   
            $publicacoes = [ 
                'publicadas' => ['dados' => $this->publicacao->getPublicadas( $publicacao_categoria_id ), 'titulo' => 'Publicadas']
            ];
            
        }        
            
        return [ 
            'publicacoes' => $publicacoes,
            'categoria' => $this->publicacaoCategoria->getCategoriaNome( $publicacao_categoria_id ),
            'can' => $this->getPermissaoUsuario()
        ];
    }


    private function getPermissaoUsuario(){
        return [
                 'visualizar' => \Auth::user()->can( 'BASE_PUBLICACOES_VISUALIZAR' ),
                 'cadastrar' => \Auth::user()->can( 'BASE_PUBLICACOES_CADASTRAR' ),
                 'editar' => \Auth::user()->can( 'BASE_PUBLICACOES_EDITAR' ),
                 'conteudo_editar' => \Auth::user()->can( 'BASE_PUBLICACOES_CONTEUDO_EDITAR' ),
                 'ativar' => \Auth::user()->can( 'BASE_PUBLICACOES_ATIVAR' ),
                 'historico' => \Auth::user()->can( 'BASE_PUBLICACOES_HISTORICO' ),
                 'historico_excluir' => \Auth::user()->can( 'BASE_PUBLICACOES_HISTORICO_APAGAR' ),
                 'restricao' => \Auth::user()->can( 'BASE_PUBLICACOES_RESTRICAO_EDITAR' ),
                 'rascunho' => \Auth::user()->can( 'BASE_PUBLICACOES_RASCUNHO' ),
                 'exportarVisualizacao' => \Auth::user()->can( 'BASE_EXPORTACOES_VISUALIZACOES' ),
                ];
    }

    /**
     * Este metodo Altera a imagem da capa de Publicação, sendo que já existe uma default
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param App\Http\Controllers\Core\ImagemController $imagem
     * @return JSON
     */
    public function changeImagemCapa(Request $request, $id, ImagemController $imagem)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        $this->validaCargo( $id );

        try{

            $file = $request->imagem;            

            $this->publicacao->where('id', $id)
                ->update( 
                 [
                    'usuario_alteracao_id' => \Auth::user()->id,
                    'imagem' => $imagem->geraImagemBase64( $file, 800, 'jpeg' )
                 ]
            );

        }catch( \Exception $e ){
            return \Response::json(['errors' => [$e->getMessage()]], 404);
        }

        return [
            'mensagem' => $this->success->msgStoreImagem(),
            'publicacao_id' => $id
        ];
    }

    private function trataDataDatepicker( $data ){
        if( !is_null( $data ) ){
            if( strtotime( $data ) ){ //nao altera o datepicker
                return date( 'd/m/Y', strtotime( $data));
            }else{ //altera o datepicker
                return $data;
            }
        }

        return $data;
    }

    /**
     * Este metodo Altera a imagem da capa de Publicação, sendo que já existe uma default
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param App\Http\Controllers\Core\ImagemController $imagem
     * @return JSON
     */
    public function setPublicacaoDatas(Request $request, $id){

        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        $messages = array(
            'observacao.required'=>'O campo Observação e obrigatório.'            
        );
        
        $rules = array(
            'observacao' => 'required'
        );        
        
        //valida campo obrigatório, observação.
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return \Response::json( ['errors' => $validator->messages() ] ,404);
        }

        //compara valores para validar regra de observação        
        $publicacaoOld = $this->publicacao->where('id', $id)->get();

        //verifica se houve alteracao
        if( 
            $publicacaoOld[0]->dt_publicacao == $this->trataDataDatepicker( $request->dt_publicacao ) && 
                $publicacaoOld[0]->dt_ultima_atualizacao == $this->trataDataDatepicker( $request->dt_ultima_atualizacao ) && 
                    $publicacaoOld[0]->dt_desativacao == $this->trataDataDatepicker( $request->dt_desativacao ) && 
                        $publicacaoOld[0]->dt_revisao == $this->trataDataDatepicker( $request->dt_revisao ) ){
            return \Response::json(['errors' => [ [ 0 => 'Para editar a publicação, é necessário alterar alguma data.'] ] ], 400);
        }  

        $this->validaCargo( $id );

        $datas_old = $this->publicacao->getPublicacaoDatas($id);

        $this->publicacao->where('id', $id)
            ->update( 
             [
                'usuario_alteracao_id' => \Auth::user()->id,
                'dt_publicacao' => $request->dt_publicacao,
                'dt_ultima_atualizacao' => $request->dt_ultima_atualizacao,
                'dt_desativacao' => $request->dt_desativacao,
                'dt_revisao' => $request->dt_revisao
             ]
        );
         
        $datas_new = $this->publicacao->getPublicacaoDatas($id);

        $observacao = $this->formatString()->strToUpperCustom($request->observacao);

        $this->publicacaoHistorico->setHistoricoDataPublicacao( $datas_old, $datas_new, $id,   $observacao  );
        
        return [
            'mensagem' => $this->success->msgUpdate( $this->entidade ),
            'publicacao_id' => $id
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

    public function chamaViewNovasPublicacoes(){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();

        return view('base-de-conhecimento.index', [
            'categoria_id' => 0,
            'migalhas' => $this->migalhaDePao('false'),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'novas_publicacoes' => true,
            'can_categoria' => json_encode($this->publicacao->getPermissaoCategoria()),
            'icones' => $icones
        ]);
    }

    public function getNovasPublicacoes( Request $request ){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );        
       
        return \Response::json( [
            'publicacoes' => $this->publicacao->getPublicacoesUltimos30Dias( $request->imagem),            
            'migalhas'    => $this->migalhaDePao('false'),            
            'can'         => $this->getPermissaoHabilitarEdicao()
        ] , 200);
    }

    public function getImagemPublicacao($id){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $this->validaCargo( $id );

        $base64 = $this->publicacao->getImagemPublicacaoBase($id);

        if ( !empty($base64) ){

            return ImagemController::getResponse($base64);
        }
        return null;

    }

    /**
     * [getPermissaoExportarDados Verifica se usuário possui permissão para exportar dados das publicações]
     * @return [bool]
     */
    protected function getPermissaoExportarDados(){
        
        $can_exportar_dados = \Auth::user()->can( 'BASE_EXPORTACOES_PUBLICACOES' );

        if( $can_exportar_dados ){
            return true;
        }

        return false;
    }

    private function validaCargo( $id )
    {
        if( !$this->publicacao->getModoEdicaoAtivos() &&
             count( $this->publicacaoCargo->getIdPublicacoesBloqueadas()->where('publicacao_id', $id) ) > 0 )
        {
            abort(403, 'Falha na autenticação.');
        }
    }
    
    /**
     * [getPermissaoExportarPesquisa Verifica se usuário possui permissão para exportar pesquisa das publicações]
     * @return [bool]
     */
    protected function getPermissaoExportarPesquisa(){
        
        $can_exportar_pesquisa = \Auth::user()->can( 'BASE_EXPORTACOES_PESQUISAS' );

        if( $can_exportar_pesquisa ){
            return true;
        }

        return false;

    }

    /**
     * Método generico para exibir conteúdo da publicacao.
     *
     * @param  int  $id identificador enviado pelo Request
     * @param  array  $param parâmetros para validações específicas do método da chamada
     * @return \Illuminate\Http\Response
     */
    private function showEditConteudo( $id, $param = [] )
    {
        if( !$this->publicacao->getModoEdicaoAtivos() )
            return redirect('base-de-conhecimento/publicacoes/'.$id );
       
        $publicacao = $this->publicacao->getCategoriaId($id);

        if( empty($publicacao) )
        {
            return $this->naoEncontrado();
        }

        $this->validaCargo( $id );

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();
        
        return view('base-de-conhecimento.editar', [
            'publicacao_id' => $id,
            'categoria_id' => $publicacao->publicacao_categoria_id, // RETORNAR A CATEGORIA DA PUBLICACAO
            'publicacao' => $this->publicacao->getPublicacao($id),
            'datas' => $this->publicacao->getPublicacaoDatas($id),
            'funcionarios' => $this->funcionario->getFuncionariosAtivos(),
            'colaboradores' => $param['colaboradores'],
            'migalhas' => $this->migalhaDePao('false'),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'cargos' => $this->cargo->getCargos(),
            'publicacao_cargos' => $this->publicacaoCargo::where('publicacao_id', $id)->get(),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'can_categoria' => json_encode($this->publicacao->getPermissaoCategoria()),
            'request' => (isset($param['request']) ? $param['request'] : ''),
            'existe_rascunho' => (isset($param['existe_rascunho']) ? $param['existe_rascunho'] : 'false'),
            'icones' => $icones
        ]);

    }

    /**
     * Exibe o rascunho de uma publicacao.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rascunho($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_RASCUNHO' );

        $param = ['request' => 'rascunho', 'existe_rascunho' => 'false'];

        $colaboradores = $this->publicacaoColaborador->getColaboradorPublicacao($id, true);

        $param['colaboradores'] = $colaboradores->values();

        if( count($colaboradores) > 0 ||
            count($this->publicacaoTag->getTagsPublicacao($id, true)) > 0 ||
            count($this->publicacaoConteudo->getConteudoPublicacao($id, true)) > 0 )
        {
            $param['existe_rascunho'] = 'true';
        }

        return $this->showEditConteudo($id, $param);
    }

    /**
     * Exibe o rascunho de uma publicacao.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setRascunho($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_RASCUNHO' );

        $publicacao = $this->publicacao->getCategoriaId($id);

        if( empty($publicacao) )
        {
            return $this->naoEncontrado();
        }

        $this->validaCargo( $id );

        DB::beginTransaction();

        try
        {          
            $colaboradores = $this->publicacaoColaborador->getColaboradorPublicacao($id, true)->count();
            $tags = $this->publicacaoTag->getTagsPublicacao($id, true)->count();
            $publicacoes = $this->publicacaoConteudo->getConteudoPublicacao($id, true)->count();

            // Validação para não duplicar registros
            if( $colaboradores == 0 && $tags == 0 && $publicacoes == 0 )
            {
                $colaboradoresRascunho = $this->publicacaoColaborador->getColaboradorPublicacaoRascunho($id, false, \Auth::user()->id);
                $this->publicacaoColaborador->insertRascunho( $colaboradoresRascunho );

                $tagsRascunho = $this->publicacaoTag->getTagsPublicacaoRascunho($id, false, \Auth::user()->id);
                $this->publicacaoTag->insertRascunho( $tagsRascunho );

                $publicacoesRascunho = $this->publicacaoConteudo->getConteudoPublicacaoRascunho($id, false, \Auth::user()->id);
                $this->publicacaoConteudo->insertRascunho( $publicacoesRascunho );

                $this->publicacaoHistorico->setHistoricoPublicacaoRascunho( $id, $tagsRascunho->count(), $colaboradoresRascunho->count(), $publicacoesRascunho->count() );
            }

            DB::commit();

            return 0;
        }
        catch(\Exception $e)
        {
            DB::rollback();

            return  0;
        }

    }    
}    
