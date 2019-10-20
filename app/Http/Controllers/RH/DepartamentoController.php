<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Http\Requests\RH\DepartamentosRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use App\Repositories\RH\DepartamentoRepositoryInterface;
use App\Repositories\RH\FuncionarioRepositoryInterface;
use App\Repositories\RH\AreaRepositoryInterface;

use App\Http\Controllers\Core\ExcelController;

use App\Models\RH\Departamento;
use App\Models\RH\Funcionario;

class DepartamentoController extends Controller
{

    protected $repository;
    protected $funcionarioRepository;
    protected $areaRepository;

    private $entidade = 'Departamento';
    private $errors;
    private $success;


    public function __construct(
        DepartamentoRepositoryInterface $repository, 
            FuncionarioRepositoryInterface $funcionarioRepository,
                AreaRepositoryInterface $areaRepository,
                Errors $errors,
                    Success $success)
    {
        $this->repository = $repository;
        $this->funcionarioRepository = $funcionarioRepository;
        $this->areaRepository = $areaRepository;
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
        $this->autorizacao( 'RH_DEPARTAMENTO_VISUALIZAR' );
        
         $gestores = $this->funcionarioRepository->scopeQuery(function($query){
                return $query->orderBy('nome', 'asc');
            })->findWhere([ ['ativo', '=', true] ], ['id', 'nome']);
            
            $areas = $this->areaRepository->scopeQuery(function($query){
              
                return $query->orderBy('nome', 'asc');
            })->findWhere([ ['ativo', '=', true] ], ['id', 'nome']);


        return view('rh.departamento.index', [
            'gestores' => $gestores,
            'areas' => $areas,
            'ativo' => 'true', 
            'filtro' => false,
            'migalhas' => $this->migalhaDePao( 'INDEX' )
        ] );
    }

    /**
     * Tela de pesquisa com filtro
     */
    public function search()//ok
    {
     
        $this->autorizacao( 'RH_DEPARTAMENTO_VISUALIZAR' );
       
       // dd(input::all());

        try{

            $string = new Str;
            $nome = $string->upper( Input::get('nome') );
            $order = input::get('ordem');

            $departamentos = $this->repository->paginacao(
                input::get('limite'), 
                input::get('to'), 
                [ 
                    //utilizar DB::raw quando for usar uma funcao do banco
                    [\DB::raw("sem_acento(\"nome\")"), 'LIKE', \DB::raw("sem_acento('%".$nome."%')") ],
                    ['ativo', '=', input::get('ativo') ],
                    ['ticket', is_null(Input::get('ticket')) ? '!=' : '=', input::get('ticket') ],
                    ['funcionario_id', 
                        is_null(Input::get('gestor.id')) ? '!=' : '=',
                        is_null(Input::get('gestor.id')) ? 0 : (int)Input::get('gestor.id')
                    ],
                    ['area_id', 
                        is_null(Input::get('area.id')) ? '!=' : '=',
                        is_null(Input::get('area.id')) ? 0 : (int)Input::get('area.id')
                    ]
                ],
                input::get('coluna'),
                $order
            );

            foreach ($departamentos as $departamento)
            {
                $departamento->gestor = $departamento->funcionario->nome;
                $departamento->area_departamento = $departamento->area->nome;
            }
        }
        catch(\Exception $e)
        {
            return \Response::json( ['errors' => $this->errors->msgSearch()] ,404);
        }

        return [ 
            'departamentos' => $departamentos,
            'gestor' => is_null(Input::get('gestor.id')) ? 0 : Input::get('gestor.id'),
            'area' => is_null(Input::get('area.id')) ? 0 : Input::get('area.id')  
        ];            
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $this->autorizacao( 'RH_DEPARTAMENTO_CADASTRAR' );
      try{

            $gestores = $this->funcionarioRepository->scopeQuery(function($query){
                return $query->orderBy('nome', 'asc');
            })->findWhere([ ['ativo', '=', true] ], ['id', 'nome']);
            
            $areas = $this->areaRepository->scopeQuery(function($query){
              
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

        return view('rh.departamento.cadastrar', [
            'gestores' => $gestores, 
            'areas' => $areas,
            'migalhas' => $this->migalhaDePao()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartamentosRequest $request)
    {
         $this->autorizacao( 'RH_DEPARTAMENTO_CADASTRAR' );
           
        try
        {
            $string = new Str;
            $departamento = $this->repository->create( 
                [ 
                    'nome' => $string->upper($request->nome),
                    'descricao' => $string->upper($request->descricao),
                    'area_id' => $request->area,
                    'funcionario_id' => $request->gestor,
                    'ticket' => $request->ticket
                ] 
            );
        }
        catch(\Exception $e)
        {
            return \Response::json(['errors' => [$this->errors->msgStore( $this->entidade )] ], 500);
        }

        return ['status'=>true, 'mensagem' => $this->success->msgStore( $this->entidade ), 'id' => $departamento->id ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $this->autorizacao( 'RH_DEPARTAMENTO_VISUALIZAR' );
        
        try{
            $departamento = new Departamento;
            $departamento = $this->repository->find($id);
            $departamento->gestor = $departamento->funcionario->nome;
            $departamento->area_departamento = $departamento->area->nome;
            
            if( $departamento->created_at == '' )
            {
                $departamento->data_inclusao = '-';
            }
            else
            {
                $departamento->data_inclusao = date_format(date_create($departamento->created_at), 'd/m/Y H:i:s');
            }
            
            if( $departamento->updated_at == '' )
            {
                $departamento->data_alteracao = '-';
            }
            else
            {
                $departamento->data_alteracao = date_format(date_create($departamento->updated_at), 'd/m/Y H:i:s');
            }
           
            if(is_null($departamento->usuarioInclusao))
            {
                $departamento->usuario_inclusao = '';
            }
            else
            {
                $departamento->usuario_inclusao = '('.$departamento->usuarioInclusao->nome.')';
            }

            if( is_null($departamento->usuarioAlteracao) )
            {
                $departamento->usuario_alteracao = '';
            }
            else
            {
                $departamento->usuario_alteracao = '('.$departamento->usuarioAlteracao->nome.')';
            }

             if( $departamento->ticket == 'true')
            {
                $departamento->ticket = 'SIM';
            }
            else
            {
               $departamento->ticket = 'NÃO';
            }

        } catch(\Exception $e){

            return response()->view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
            ], 404 );
        }

        return view( 'rh.departamento.visualizar', [
            'migalhas' => $this->migalhaDePao(),
            'departamento' => $departamento
        ] );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->autorizacao( 'RH_DEPARTAMENTO_EDITAR' );
        
        try
        {
            $departamento = $this->repository->find($id);
            $departamento->gestor = $departamento->funcionario->nome;
            $departamento->area_departamento = $departamento->area->nome;
            
            $gestores = $this->funcionarioRepository->scopeQuery(function($query){
                return $query->orderBy('nome', 'asc');
            })->findWhere([ ['ativo', '=', true] ], ['id', 'nome']);
            
            $areas = $this->areaRepository->scopeQuery(function($query){
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

        return view( 'rh.departamento.editar', [
            'migalhas' => $this->migalhaDePao(),
            'departamento' => $departamento,
            'areas' => $areas,
            'gestores' => $gestores
        ] );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DepartamentosRequest $request, $id)
    {
        $this->autorizacao( 'RH_DEPARTAMENTO_EDITAR' );
        

        try
        {
            $string = new Str;

            $request['nome'] = $string->upper($request->nome);
            $request['descricao'] = $string->upper($request->descricao);
            $request['funcionario_id'] = $request->gestor['id']; 
            $request['area_id'] = $request->area['id'];
            $request['ticket'] = $request->ticket;

           $this->repository->update($request->all(), $id);

        }
        catch( \Exception $e )
        {
            return \Response::json(['errors' => $this->errors->msgUpdate( $this->entidade ) ], 404);
        }

        return ['status'=>true, 'mensagem' => $this->success->msgUpdate( $this->entidade ), 'id' => $id ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $this->autorizacao( 'RH_DEPARTAMENTO_STATUS' );

         try{

            $this->repository->destroy( $id );                
            
        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);
        }
    }
}