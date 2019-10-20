<?php

namespace App\Http\Controllers\Configuracao\Ticket;


use App\Http\Requests\Configuracao\Ticket\CategoriaRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\RH\DepartamentoRepositoryInterface;
use App\Repositories\Configuracao\Ticket\CategoriaRepositoryInterface;

use Illuminate\Support\Str;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;
//use App\Models\Configuracao\Ticket\Categoria;

class CategoriaController extends Controller
{
    protected $departamentoRepository;
    protected $repository;
    private $entidade = 'Categoria';
    private $errors;
    private $success;

     public function __construct(
         DepartamentoRepositoryInterface $departamentoRepository,
            CategoriaRepositoryInterface $repository,
                Errors $errors,
                    Success $success)
    {
      
      $this->repository = $repository;
      $this->departamentoRepository = $departamentoRepository;
      $this->errors = $errors;
      $this->success = $success;

       $this->departamentos = $this->departamentoRepository->scopeQuery(function($query){
            return $query->orderBy('nome', 'asc');
        })->findWhere([  ['ticket', '=', 'true'], ['ativo', '=', 'true'] ], ['id', 'nome']);
      $this->page = "categoria";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $this->autorizacao('CONFIGURACAO_TICKET_ACOES_VISUALIZAR');        
        
        return view( 'configuracao.ticket.categoria.index', [ 
            'departamentos' => $this->departamentos,
            'departamento' => 'false',
            'edit' => false,
            'categorias_filha' => 'false',
            'categorias_pai' => 'false',
            'page' => $this->page,
            'migalhas' => $this->migalhaDePao( 'CONFIGURAR' ) 

        ] );
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
    public function store(CategoriaRequest $request)
    {
        $this->autorizacao('CONFIGURACAO_TICKET_CATEGORIAS_CADASTRAR');        

        try
        {
            $string = new Str;
            $categoria = $this->repository->create( 
                [ 
                    'nome' => $string->upper($request->nome),
                    'descricao' => $string->upper($request->descricao),
                    'dicas' => $string->upper($request->dicas),
                    'ticket_categoria_id' => $request->ticket_categoria_id,
                    'departamento_id' => $request->departamento_id
                ] 
            );
        }
        catch(\Exception $e)
        {
             return redirect()->to('/configuracao/ticket/categoria/departamento/'.$request->departamento_id)->send();
        }

        return redirect()->to('/configuracao/ticket/categoria/departamento/'.$request->departamento_id)->send();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->autorizacao('CONFIGURACAO_TICKET_CATEGORIAS_VISUALIZAR'); 

         try{

        $categorias_pai = $this->repository->scopeQuery(function($query){
            return $query->orderBy('nome', 'asc');
        })->findWhere([
            ['departamento_id', '=', $id],
            'ticket_categoria_id' => null ,
            ['ativo', '=', 'true']
         ], ['id', 'nome', 'descricao', 'dicas', 'ticket_categoria_id']);

        $categorias_filha = $this->repository->scopeQuery(function($query){
            return $query->orderBy('nome', 'asc');
        })->findWhere([
            ['departamento_id', '=', $id],
            ['ticket_categoria_id', '!=', null ],
            ['ativo', '=', 'true']
         ], ['id', 'nome', 'descricao', 'ticket_categoria_id', 'dicas' ]);

        }catch(\Exception $e){

            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
                ] );
        }

        return view( 'configuracao.ticket.categoria.index', [ 
            'departamentos' => $this->departamentos,
            'departamento' => $id,
            'edit' => true,
            'categorias_filha' => $categorias_filha,
            'categorias_pai' => $categorias_pai,
            'page' => $this->page,
            'migalhas' => $this->migalhaDePao( 'CONFIGURAR' ) 
        ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->autorizacao('CONFIGURACAO_TICKET_CATEGORIAS_EDITAR');        
        if( $request->id != $request->ticket_categoria_id){

            try
            {
                $string = new Str;
                    $this->repository->update([
                        'nome'=> $string->upper($request->nome),
                        'descricao'=> $string->upper($request->descricao),
                        'dicas'=> $string->upper($request->dicas),
                        'ticket_categoria_id' => $request->ticket_categoria_id
                                ], $request->id);

            } catch(\Exception $e)
            {   
                return redirect()->to('/configuracao/ticket/categoria/departamento/'.$id)->send();
            }
            
        }
         return redirect()->to('/configuracao/ticket/categoria/departamento/'.$id)->send();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->autorizacao('CONFIGURACAO_TICKET_CATEGORIAS_EXCLUIR'); 

        $this->repository->destroy( $id );                
            
        

    }
}
