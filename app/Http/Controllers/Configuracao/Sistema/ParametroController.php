<?php

namespace App\Http\Controllers\Configuracao\Sistema;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuracao\Sistema\ParametroRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Input;

use App\Models\Configuracao\Usuario;

use App\Repositories\Configuracao\Sistema\ParametroRepositoryInterface;
use App\Repositories\Configuracao\Sistema\ParametroGrupoRepositoryInterface;
use App\Repositories\Configuracao\Sistema\ParametroTipoRepositoryInterface;
use App\Repositories\Configuracao\Sistema\ParametroViewRepositoryInterface;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Models\Configuracao\Sistema\Parametro;

class ParametroController extends Controller
{
    protected $repository;
    protected $grupoRepository;
    protected $tipoRepository;
    protected $parametroViewRepository;

    private $entidade = 'Parâmetro';
    private $errors;
    private $success;

    public function __construct(
        ParametroRepositoryInterface $repository,
            ParametroGrupoRepositoryInterface $grupoRepository,
                ParametroTipoRepositoryInterface $tipoRepository,
                    ParametroViewRepositoryInterface $parametroViewRepository,
                        Errors $errors,
                            Success $success )
    {
        $this->repository = $repository;
        $this->grupoRepository = $grupoRepository;
        $this->tipoRepository = $tipoRepository;
        $this->parametroViewRepository = $parametroViewRepository;
        $this->errors = $errors;
        $this->success = $success;
    }

    public function index()//ok
    {
        $this->autorizacao('CONFIGURACAO_SISTEMA_PARAMETRO_VISUALIZAR');

        try
        {
            $grupos = $this->grupoParametrosClass();
            $tipos = $this->tipoParametrosClass();           
        }
        catch( \Exception $e )
        {            
            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ) ] );
        }        

        return view('configuracao.sistema.parametro.index', [
            'grupos' => $grupos, 
            'tipos' => $tipos, 
            'ativo' => 'true', 
            'filtro' => 'false', 
            'migalhas' => $this->migalhaDePao( 'INDEX' ) ]
        );
    }
   
    /**
     * metodo de busca
     */
    public function search()//ok
    {
        $this->autorizacao('CONFIGURACAO_SISTEMA_PARAMETRO_VISUALIZAR');

        $string = new Str;
        $nome = $string->upper( Input::get('nome') );
        
        try{
            
            $parametros = $this->parametroViewRepository->paginacao(
                input::get('limite'), 
                input::get('to'), 
                [ 
                    //utilizar DB::raw quando for usar uma funcao do banco
                    [\DB::raw("sem_acento(\"nome\")"), 'LIKE', \DB::raw("sem_acento('%".$nome."%')") ],
                    ['ativo', '=', input::get('ativo')],
                    ['parametro_grupo_id', 
                        is_null(Input::get('grupo.id'))?'!=':'=',
                        is_null(Input::get('grupo.id'))?0:(int)Input::get('grupo.id')
                    ],
                    ['parametro_tipo_id', 
                        is_null(Input::get('tipo.id'))?'!=':'=',
                        is_null(Input::get('tipo.id'))?0:(int)Input::get('tipo.id')
                    ]
                ],
                input::get('coluna'),
                input::get('ordem'),
                [
                    'id', 
                    'nome', 
                    'ativo', 
                    'ordem', 
                    'editar',
                    'descricao', 
                    'valor_texto', 
                    'valor_numero', 
                    'valor_booleano', 
                    'parametro_grupo_id', 
                    'parametro_tipo_id',
                    'grupo_nome' ,
                    'tipo_nome'                    
                ]);            

        }
        catch(\Exception $e)
        {
            return view( 'erros.naoEncontrado', [                
                'titulo' => $this->entidade, 
                'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
                'mensagem' =>  $this->errors->msgSearch()
                ] );
        }

        return [
            'parametros' => $parametros,
            'grupo' => Input::has('grupo.id') ? Input::get('grupo.id') : 0,
            'tipo' => Input::has('tipo.id') ? Input::get('tipo.id') : 0
        ];
    }

    /**
     * retorna a View() para cadastrar Parâmetro
     */
    public function create()//ok
    {
        $this->autorizacao('CONFIGURACAO_SISTEMA_PARAMETRO_CADASTRAR');

        try{

            $grupos = $this->grupoParametrosClass();
            $tipos = $this->tipoParametrosClass();

        }catch(\Exception $e){
            return view( 'erros.naoEncontrado', 
                [
                    'titulo' => $this->entidade, 
                    'descricao' => $this->errors->descricaoCadastrar( $this->entidade ), 
                    'mensagem' => $this->errors->msgStore( $this->entidade, $e->getMessage() ) 
                ] );
        }

        return view('configuracao.sistema.parametro.cadastrar', ['grupos' => $grupos, 'tipos' => $tipos, 'migalhas' => $this->migalhaDePao()]);
    }

    /**
     * Inserir dados de novo parametro
     */
    public function store(ParametroRequest $request)//ok
    {

        $this->autorizacao('CONFIGURACAO_SISTEMA_PARAMETRO_CADASTRAR');

        $string = new Str;

        try{

            if( $request->tipo['nome'] == 'TEXTO' )
            {
                $valor = 'valor_texto';
                $request->valor = $string->upper( $request->valor );
            }
            else if( $request->tipo['nome'] == 'NÚMERO' )
            {
                $valor = 'valor_numero';
                $this->valorNumero( $request->valor );
            }
            else if( $request->tipo['nome'] == 'BOOLEANO' )
            {
                $valor = 'valor_booleano';
                $request->valor = $this->valorBooleano( $string->upper($request->valor) );
            }

            $parametro = $this->repository->create( 
                [ 
                    'nome' => $string->upper( $request->nome ),
                    'descricao' => $string->upper( $request->descricao ),
                    'parametro_grupo_id' => $request->grupo['id'],
                    'parametro_tipo_id' => $request->tipo['id'],
                    'ordem' => $request->ordem,
                    $valor => $request->valor,
                    'editar' => $request->editar
                ] 
            );
        }
        catch(\App\Exceptions\ParametroException $e){

            return \Response::json(['errors' => $e->getMessage() ] ,404);
        }
        catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgStore( $this->entidade ) ] ,404);
        }

        return ['status'=>true, 'mensagem' =>  $this->success->msgStore( $this->entidade ), 'id' => $parametro->id ];
    }

    private function valorNumero( $valor='' )
    {
        if( !is_numeric( $valor ) )
        {
            throw new \App\Exceptions\ParametroException('O valor digitado não é um número.');
        }
        else
        {
            return $valor;
        }
    }

    private function valorBooleano( $valor='' )
    {
        if( $valor == 'VERDADEIRO' || $valor == 'FALSO'  )
        {
            if( $valor == 'VERDADEIRO' )
            {
                return true;
            }
            else if( $valor == 'FALSO' )
            {
                return false;
            }
        }
        else
        {
            throw new \App\Exceptions\ParametroException('Por favor digite um valor "VERDADEIRO" ou "FALSO".');
        }
    }

    /**
     * Retorna dados do parametro para edição
     */
    public function edit( $id )//ok
    {
        $this->autorizacao('CONFIGURACAO_SISTEMA_PARAMETRO_EDITAR');

        try{

            $parametro = $this->repository->find($id, 
                [
                    'id', 
                    'nome', 
                    'ativo', 
                    'ordem',
                    'editar', 
                    'descricao',
                    'valor_texto', 
                    'valor_numero', 
                    'valor_booleano', 
                    'parametro_grupo_id', 
                    'parametro_tipo_id',
                    'obrigatorio'
                ]);
            
            $grupo = $this->grupoParametrosClass();

            $tipo = $this->tipoParametrosClass();

            $grupo_selected = $this->grupoRepository->findWhere([ ['ativo', '=', 'TRUE'], ['id', '=',  $parametro['parametro_grupo_id'] ]  ], ['id', 'nome']);

            $tipo_selected = $this->tipoRepository->findWhere([ ['ativo', '=', 'TRUE'], ['id', '=',  $parametro['parametro_tipo_id'] ]  ], ['id', 'nome']);

        }catch( \Exception $e ){

            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()] );
        }
        
        return view( 'configuracao.sistema.parametro.editar', [ 
            'parametro' => $parametro, 
            'grupos' => $grupo, 
            'tipos' => $tipo, 
            'grupo_selected' => $grupo_selected, 
            'tipo_selected' => $tipo_selected, 
            'migalhas' => $this->migalhaDePao() ] );
    }

    public function update( ParametroRequest $request, $id ){//ok

        $this->autorizacao('CONFIGURACAO_SISTEMA_PARAMETRO_EDITAR');

        try{

            $string = new Str;

            $request['valor_texto'] = NULL;
            $request['valor_numero'] = NULL;
            $request['valor_booleano'] = NULL;

            if( $request->tipo['nome'] == 'TEXTO' )
            {
                $request['valor_texto'] = $string->upper( $request->valor );
            }
            else if( $request->tipo['nome'] == 'NÚMERO' )
            {
                $request['valor_numero'] = $this->valorNumero( $request->valor );
            }
            else if( $request->tipo['nome'] == 'BOOLEANO' )
            {
                $request['valor_booleano'] = $this->valorBooleano( $string->upper($request->valor) );
            }

            $request['nome'] = $string->upper($request->nome);
            $request['descricao'] = $string->upper($request->descricao);
            $request['parametro_grupo_id'] = $request->grupo['id'];
            $request['parametro_tipo_id'] = $request->tipo['id'];             

            $this->repository->update($request->all(), $id);

        } 
        catch(\App\Exceptions\ParametroException $e){

            return \Response::json(['errors' => $e->getMessage() ] ,404);
        }
        catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgUpdate( $this->entidade ) ] ,404);
        }
        
        return ['status'=>true, 'mensagem' => $this->success->msgUpdate( $this->entidade ) ];        
    }

    private function grupoParametrosClass(){
        
        try{

            $grupos = $this->grupoRepository->scopeQuery(function($query){

                return $query->orderBy('nome','asc'); 

            })->findWhere([ ['ativo', '=', 'TRUE'] ], ['id', 'nome']);

        }catch(\Exception $e){
            
            $grupos  = false;
        }       

        return $grupos;
    }

    private function tipoParametrosClass(){
        
        try{

            $tipos = $this->tipoRepository->scopeQuery(function($query){

                return $query->orderBy('nome','asc'); 

            })->findWhere([ ['ativo', '=', 'TRUE'] ], ['id', 'nome']);

        }catch(\Exception $e){
            
            $tipos  = false;
        }       

        return $tipos;
    }
}