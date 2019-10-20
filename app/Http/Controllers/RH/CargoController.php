<?php

namespace App\Http\Controllers\RH;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use App\Repositories\RH\CargosRepositoryInterface;
use App\Repositories\RH\FuncionarioRepositoryInterface;

use App\Repositories\RH\DepartamentoRepositoryInterface;
use App\Repositories\Configuracao\UsuarioRepositoryInterface;

use App\Http\Requests\CargoRequest;

class CargoController extends Controller
{

    private $entidade = 'Cargo';    
    private $errors;
    protected $repository;
    protected $departamentosRepository;
    protected $funcionariosRepository;
    private $strAutorizacaoModulo = 'RH_CARGO_';

    public function __construct(
        CargosRepositoryInterface $repository, 
            DepartamentoRepositoryInterface $departamentosRepository,
                FuncionarioRepositoryInterface $funcionariosRepository,
                    UsuarioRepositoryInterface $usuarioRepository,
                        Errors $errors,
                            Success $success)
    {        
        $this->errors = $errors;
        $this->success = $success;
        $this->repository = $repository;
        $this->departamentosRepository = $departamentosRepository;
        $this->funcionariosRepository = $funcionariosRepository;
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->autorizacao($this->strAutorizacaoModulo.'VISUALIZAR');

        $departamentos = $this->departamentoClass();
        $funcionarios = $this->funcionarioClass();
              
        return view('rh.cargo.index', 
            [
                'ativo' => 'true',
                'departamentos' => $departamentos,
                'funcionarios' => $funcionarios,
                'filtro' => false, 
                'migalhas' => $this->migalhaDePao( 'INDEX' ) ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->autorizacao($this->strAutorizacaoModulo.'CADASTRAR');

        try{

            //dados para o select options
            $departamentos = $this->departamentoClass();
            $funcionarios = $this->funcionarioClass();            
            
        }catch( \Exception $e ){

            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoCadastrar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
                ] );            
        }

        return view('rh.cargo.cadastrar', [
            'departamentos' => $departamentos, 
            'funcionarios' => $funcionarios, 
            'migalhas' => $this->migalhaDePao()
        ] );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CargoRequest $request)
    {

        $this->autorizacao($this->strAutorizacaoModulo.'CADASTRAR');

        try{

            $string = new Str;

            $cargo = $this->repository->create( 
                [ 
                    'nome' => $string->upper($request->nome),
                    'descricao' => $string->upper($request->descricao),
                    'funcionario_id' => $request->gestor,
                    'departamento_id' => $request->departamento
                ]                 
            );

        }
        catch(\Exception $e)
        {
            return \Response::json(['errors' => $this->errors->msgStore( $this->entidade ) ], 500);
        }

        return ['status'=>true, 'mensagem' => $this->success->msgStore( $this->entidade ), 'id' => $cargo->id ];
    }

    /**
     * Pesquisa Cargo por id
     */
    public function show( $id )//ok
    {
        $this->autorizacao($this->strAutorizacaoModulo.'VISUALIZAR');

        try{

            //verifica se cargo existe, se nao existe retorna uma exception
            $cargo = $this->repository->find($id, [
                'id', 
                'departamento_id', 
                'nome', 
                'descricao',                
                'funcionario_id',
                'usuario_inclusao_id', 
                'usuario_alteracao_id', 
                'updated_at', 
                'created_at'
            ])->makeHidden( [
                'departamento_id', 
                'funcionario_id',
                'usuario_inclusao_id',
                'usuario_alteracao_id'
            ]);           
            
            $departamento = $this->departamentosRepository->find($cargo->departamento_id);

            $usuarioInclusao = $this->usuarioRepository->scopeQuery(function($query){
                    return $query->first();
                })->findWhere([ ['id', '=', $cargo->usuario_inclusao_id] ], ['nome']);

            $usuarioAlteracao = $this->usuarioRepository->scopeQuery(function($query){
                    return $query->first(); 
                })->findWhere([ ['id', '=', $cargo->usuario_alteracao_id] ], ['nome']);

            $cargo->__set("departamento", $departamento->nome);            

            $usuario_inclusao = '';
            if( isset( $usuarioInclusao[0]->nome ) ){                
                $usuario_inclusao = ' ('.$usuarioInclusao[0]->nome.')';                
            }
            $data_inclusao = date_format(date_create($cargo->created_at), 'd/m/Y H:i:s') . $usuario_inclusao;

            $usuario_alteracao = '';
            if( isset( $usuarioAlteracao[0]->nome ) ){                  
                $usuario_alteracao = ' ('.$usuarioAlteracao[0]->nome.')';
            }
            $data_alteracao = date_format(date_create($cargo->updated_at), 'd/m/Y H:i:s') . $usuario_alteracao;

            if(!is_null( $cargo->funcionario_id )) {
                $funcionario = $this->funcionariosRepository->find($cargo->funcionario_id, ['nome']);
                $cargo->__set("funcionario", $funcionario->nome);
            } else {
                $cargo->__set("funcionario", '-');
            }

        }
        catch(\Exception $e)
        {
            return response()->view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
            ], 404 );
        }

        return view('rh.cargo.visualizar', 
            [   
                'id' => $cargo->id,
                'nome' => $cargo->nome, 
                'departamento' => $cargo->departamento, 
                'funcionario' => $cargo->funcionario, 
                'descricao' => $cargo->descricao,                 
                'data_inclusao' => $data_inclusao, 
                'data_alteracao' => $data_alteracao, 
                'migalhas' => $this->migalhaDePao() 
            ]);        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->autorizacao($this->strAutorizacaoModulo.'EDITAR');

        try{

            $cargo = $this->repository->find($id, ['id', 'nome', 'descricao', 'funcionario_id', 'departamento_id']);            
            $func_selected = $this->funcionariosRepository->findWhere([ ['id', '=',  $cargo['funcionario_id'] ]  ], ['id', 'nome']);
            $depart_selected = $this->departamentosRepository->findWhere([ ['id', '=',  $cargo['departamento_id'] ]  ], ['id', 'nome']);

            //lista para o select options
            $departamentos = $this->departamentoClass();
            $funcionarios = $this->funcionarioClass();            

        }
        catch(\Exception $e)
        {
            return response()->view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
            ], 404 );
        }                   
        
        return view('rh.cargo.editar', [
            'id' => $id, 
            'nome' => json_encode($cargo->nome), 
            'descricao' => json_encode($cargo->descricao),
            'funcionarios' => json_encode($funcionarios), 
            'func_selected' => $func_selected, 
            'departamentos' => json_encode($departamentos),
            'depart_selected' => $depart_selected,
            'migalhas' => $this->migalhaDePao()
            ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CargoRequest $request, $id)
    {

        $this->autorizacao($this->strAutorizacaoModulo.'EDITAR');

        try{

            $string = new Str;

            $request['nome'] = $string->upper($request->nome);
            $request['descricao'] = $string->upper($request->descricao);
            $request['funcionario_id'] = $request->gestor['id'];
            $request['departamento_id'] = $request->departamento['id'];
            
            $cargo = $this->repository->update($request->all(), $id);

        } catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgUpdate( $this->entidade ) ], 404);
        }
        
        return ['status'=>true, 'mensagem' => $this->success->msgUpdate( $this->entidade ), 'id' => $cargo->id ];
    }

    /**
     * Tela de pesquisa com filtro
     */
    public function search()//ok
    {

        $this->autorizacao($this->strAutorizacaoModulo.'VISUALIZAR');

        try {

            $string = new Str;
            $nome = $string->upper( Input::get('nome') );            
            
            $cargos = $this->repository->paginacao(
                input::get('limite'), 
                input::get('to'), 
                [ 
                    [\DB::raw("sem_acento(\"nome\")"), 'LIKE', \DB::raw("sem_acento('%".$nome."%')") ],                    

                    ['funcionario_id', 
                        is_null(Input::get('funcionario')['id'])?'!=':'=',
                        is_null(Input::get('funcionario')['id'])?0:(int)Input::get('funcionario')['id']
                    ],

                    ['departamento_id', 
                        is_null(Input::get('departamento')['id'])?'!=':'=',
                        is_null(Input::get('departamento')['id'])?0:(int)Input::get('departamento')['id']
                    ],

                    ['ativo', '=', input::get('ativo') ]
                ],
                input::get('coluna'),
                input::get('ordem'),
                ['id', 'nome', 'descricao', 'funcionario_id', 'departamento_id', 'ativo']  
                );            

            foreach ($cargos as $cargo) {

                $departamento = $this->departamentosRepository->findWhere([ ['id', '=', $cargo->departamento_id] ]);

                if ( count($departamento) > 0 ) {
                    $cargo['departamento'] = $departamento[0]->nome;
                }
                else {
                    $cargo['departamento'] = '';
                }

                $funcionario = $this->funcionariosRepository->findWhere([ ['id', '=', $cargo->funcionario_id] ]);

                if ( count($funcionario) > 0 ) {
                    $cargo['funcionario'] = $funcionario[0]->nome;
                }
                else {
                    $cargo['funcionario'] = '';
                }
            }

        } catch(\Exception $e) {            
            return \Response::json( ['errors' => $this->errors->msgSearch()] ,404);
        }

        return [
            'cargos' => $cargos
        ];     
    }

    private function departamentoClass(){
        
        try{

            $departamentos = $this->departamentosRepository->scopeQuery(function($query){

                return $query->orderBy('nome','asc'); 

            })->findWhere([ ['ativo', '=', 'TRUE'] ], ['id', 'nome']);

        }catch(\Exception $e){
            
            throw new Exception("Error:  departamentoClass", 1);
            return ;
            
        }       

        return $departamentos;
    }

    private function funcionarioClass(){
        
        try{

            $funcionarios = $this->funcionariosRepository->scopeQuery(function($query){

                return $query->orderBy('nome','asc'); 

            })->findWhere([ ['ativo', '=', 'TRUE'] ], ['id', 'nome']);

        }catch(\Exception $e){
            
            throw new Exception("Error:  funcionarioClass", 1);
            return ;
            
        }       

        return $funcionarios;
    }

    /**
     * Deleta Cargo
     */
    public function destroy( $id )//ok
    {
        $this->autorizacao($this->strAutorizacaoModulo.'STATUS');

        try{

            return $this->repository->destroy( $id );                
            
        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);            
        }
    }
}
