<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Http\Requests\RH\AreaRequest;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Repositories\RH\AreaRepositoryInterface;
use App\Repositories\RH\FuncionarioRepositoryInterface;

use App\Models\RH\Area;
use App\Models\RH\Funcionario;

class AreaController extends Controller
{
    protected $repository;
    protected $funcionarioRepository;

    private $entidade = 'Área';
    private $errors;
    private $success;

    public function __construct(
        AreaRepositoryInterface $repository, 
            FuncionarioRepositoryInterface $funcionarioRepository,
                Errors $errors,
                    Success $success)
    {
        $this->repository = $repository;
        $this->funcionarioRepository = $funcionarioRepository;
        $this->errors = $errors;
        $this->success = $success;
    }

    public function index()//ok
    {
        $this->autorizacao( 'RH_AREA_VISUALIZAR' );

        $gestores = $this->funcionarioRepository->scopeQuery(function($query){
            return $query->orderBy('nome', 'asc');
        })->findWhere([ ['ativo', '=', true] ], ['id', 'nome']);

        return view('rh.area.index', [
            'gestores' => $gestores,
            'ativo' => 'true', 
            'filtro' => false,
            'migalhas' => $this->migalhaDePao( 'INDEX' )
        ] );
    }

    /**
     * metodo de busca
     */
    public function search()//ok
    {
        $this->autorizacao('RH_AREA_VISUALIZAR');

        try{

            $string = new Str;
            $nome = $string->upper( Input::get('nome') );
            $order = input::get('ordem');

            $areas = $this->repository->paginacao(
                input::get('limite'), 
                input::get('to'), 
                [ 
                    //utilizar DB::raw quando for usar uma funcao do banco
                    [\DB::raw("sem_acento(\"nome\")"), 'LIKE', \DB::raw("sem_acento('%".$nome."%')") ],
                    ['ativo', '=', input::get('ativo') ],
                    ['funcionario_id', 
                        is_null(Input::get('gestor.id')) ? '!=' : '=',
                        is_null(Input::get('gestor.id')) ? 0 : (int)Input::get('gestor.id')
                    ]
                ],
                input::get('coluna'),
                $order
            );

            foreach ($areas as $area)
            {
                $area->gestor = $area->funcionario->nome;
            }
        }
        catch(\Exception $e)
        {
            return \Response::json( ['errors' => $this->errors->msgSearch()] ,404);
        }

        return [ 
            'areas' => $areas,
            'gestor' => is_null(Input::get('gestor.id')) ? 0 : Input::get('gestor.id') ];            
    }

    public function create()
    {
        $this->autorizacao('RH_AREA_CADASTRAR');

        try{

            $gestores = $this->funcionarioRepository->scopeQuery(function($query){
                return $query->orderBy('nome', 'asc');
            })->findWhere([ ['ativo', '=', true] ], ['id', 'nome']);
            
        }catch( \Exception $e ){

            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoCadastrar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
                ] );            
        }

        return view('rh.area.cadastrar', ['gestores' => $gestores, 'migalhas' => $this->migalhaDePao()]);
    }

    public function store(AreaRequest $request)
    {
        $this->autorizacao('RH_AREA_CADASTRAR');

        try
        {
            $string = new Str;
            $area = $this->repository->create( 
                [ 
                    'nome' => $string->upper($request->nome),
                    'descricao' => $string->upper($request->descricao),
                    'funcionario_id' => $request->gestor
                ] 
            );
        }
        catch(\Exception $e)
        {
            return \Response::json(['errors' => $this->errors->msgStore( $this->entidade ) ], 500);
        }

        return ['status'=>true, 'mensagem' => $this->success->msgStore( $this->entidade ), 'id' => $area->id ];
    }

    public function show($id)
    {
        $this->autorizacao('RH_AREA_VISUALIZAR');

        try
        {
            $area = new Area;
            $area_all = $this->repository->find( $id );

            $area->id = $id;
            $area->nome = $area_all->nome;
            $area->descricao = $area_all->descricao;
            $area->gestor = $area_all->funcionario->nome;

            if( $area_all->created_at == '' )
            {
                $area->data_inclusao = '-';
            }
            else
            {
                $area->data_inclusao = date_format(date_create($area_all->created_at), 'd/m/Y H:i:s');
            }
            
            if( $area_all->updated_at == '' )
            {
                $area->data_alteracao = '-';
            }
            else
            {
                $area->data_alteracao = date_format(date_create($area_all->updated_at), 'd/m/Y H:i:s');
            }
           
            if(is_null($area_all->usuarioInclusao))
            {
                $area->usuario_inclusao = '';
            }
            else
            {
                $area->usuario_inclusao = '('.$area_all->usuarioInclusao->nome.')';
            }

            if( is_null($area_all->usuarioAlteracao) )
            {
                $area->usuario_alteracao = '';
            }
            else
            {
                $area->usuario_alteracao = '('.$area_all->usuarioAlteracao->nome.')';
            }
        }
        catch( \Exception $e )
        {
            return response()->view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
            ], 404 );
        }

        return view( 'rh.area.visualizar', [
            'migalhas' => $this->migalhaDePao(),
            'area' => $area
        ] );
    }

    public function edit($id)
    {
        $this->autorizacao('RH_AREA_EDITAR');

        try
        {
            $area = new Area;
            $area_all = $this->repository->find( $id );

            $area->id = $id;
            $area->nome = $area_all->nome;
            $area->descricao = $area_all->descricao;
            $area->gestor_id = $area_all->funcionario->id;
            $area->gestor = $area_all->funcionario->nome;

            $gestores = $this->funcionarioRepository->scopeQuery(function($query){
                return $query->orderBy('nome', 'asc');
            })->findWhere([ ['ativo', '=', true] ], ['id', 'nome']);
        }
        catch( \Exception $e )
        {
            return response()->view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
            ], 404 ); 
        }

        return view( 'rh.area.editar', [
            'migalhas' => $this->migalhaDePao(),
            'area' => $area,
            'gestores' => $gestores
        ] );
    }

    public function update(AreaRequest $request, $id)
    {
        $this->autorizacao('RH_AREA_EDITAR');

        try
        {
            $string = new Str;

            $request['nome'] = $string->upper($request->nome);
            $request['descricao'] = $string->upper($request->descricao);
            $request['funcionario_id'] = $request->gestor['id'];

            $this->repository->update($request->all(), $id);
        }
        catch( \Exception $e )
        {
            return \Response::json(['errors' => $this->errors->msgUpdate( $this->entidade ) ], 404);
        }

        return ['status'=>true, 'mensagem' => $this->success->msgUpdate( $this->entidade ), 'id' => $id ];
    }

    /**
     * Muda o Status
     */
    public function destroy( $id )//ok
    {
        $this->autorizacao('RH_AREA_STATUS');

        try{

            $this->repository->destroy( $id );                
            
        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);
        }
    }
}
