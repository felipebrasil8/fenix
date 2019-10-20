<?php

namespace App\Http\Controllers\Configuracao\Ticket;

use Illuminate\Http\Request;

use App\Repositories\RH\DepartamentoRepositoryInterface;
use App\Repositories\Configuracao\Ticket\StatusRepositoryInterface;
use App\Repositories\Configuracao\Ticket\PrioridadeRepositoryInterface;
use App\Repositories\Configuracao\Ticket\CampoRepositoryInterface;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;


use App\Http\Requests\Configuracao\Ticket\CampoAdicionalPrioridadeRequest;
use App\Http\Requests\Configuracao\Ticket\CampoAdicionalStatusRequest;

use App\Http\Controllers\Core\Success;
use App\Http\Controllers\Core\Errors;

class CampoAdicionalController extends Controller
{

    protected $departamentoRepository;
    protected $ticketStatusRepository;
    protected $ticketCampoRepository;   


    private $strAutorizacaoModulo = 'CONFIGURACAO_TICKET_CAMPOS_';
    private $entidade = 'Campo Adicional';
    private $entidadePrioridade = 'Campo Prioridade';
    private $entidadeStatus = 'Campo Status';
    private $page = 'campos';

    public function __construct(
        DepartamentoRepositoryInterface $repository,
            StatusRepositoryInterface $ticketStatusRepository,
                PrioridadeRepositoryInterface $ticketPrioridadeRepository,
                    CampoRepositoryInterface $ticketCampoRepository,
                    Errors $errors,
                                Success $success)
    {
        $this->departamentoRepository     = $repository;
        $this->ticketStatusRepository     = $ticketStatusRepository;
        $this->ticketPrioridadeRepository = $ticketPrioridadeRepository;
        $this->ticketCampoRepository      = $ticketCampoRepository;
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
        $this->autorizacao('CONFIGURACAO_TICKET_CAMPOS_VISUALIZAR');

        $departamentos = $this->getDepartamentos();

        return view( 'configuracao.ticket.campo_adicional.index', [ 
            'departamentos' => $departamentos,
            'departamentos_selected' => 'false',
            'mostrar_campo' => false,
            'campos_adicionais' => 'false',
            'migalhas' => $this->migalhaDePao( 'CONFIGURAR' ),
            'prioridades' => false,
            'prioridade'=>false,
            'statu'=>false,
            'status' => false,
            'page' => $this->page,
            'departamento_id' => false
        ] );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $this->autorizacao('CONFIGURACAO_TICKET_CAMPOS_CADASTRAR');        

        try{            

            $string = new Str;

            if( $request->tipo == 'TEXTO LONGO' || $request->tipo == 'TEXTO' ){

                if( $request->tipo == 'TEXTO LONGO'){
                    $padrao = $string->upper($request->texto_longo);
                }else if( $request->tipo == 'TEXTO'){
                    $padrao = $string->upper($request->texto);
                }

                $campo = $this->ticketCampoRepository->create( 
                    [ 
                        'nome' => $string->upper( $request->nome ),
                        'descricao' => $string->upper($request->descricao),
                        'visivel' => $request->visivel,
                        'obrigatorio' => $request->obrigatorio,
                        'departamento_id' => $request->departamento_id,                    
                        'padrao' => $padrao,
                        'tipo' => $request->tipo,
                        'dados' => 'null',
                    ]
                );

            }else{

                $padrao=null;
                if( $request->lista != null ){

                    $i=0;
                    $elementos=json_decode( $request->lista);
                    foreach ( $elementos as $lista) {                        

                        $elementos[$i]->valor= $string->upper( $request->lista_texto_cadastrar[$i] );

                                                                        
                        if( $lista->padrao == 'true' ){                            
                            $padrao = $lista->id;
                        } 
                        $i++;                                       
                    }                    

                }else{
                    
                    $elementos = json_decode('[{"id":0,"padrao":"false","valor":""}]');
                    $padrao=null;
                }                

                $campo = $this->ticketCampoRepository->create( 
                    [ 
                        'nome' => $string->upper( $request->nome ),
                        'descricao' => $string->upper($request->descricao),
                        'visivel' => $request->visivel,
                        'obrigatorio' => $request->obrigatorio,
                        'departamento_id' => $request->departamento_id,                    
                        'padrao' => $padrao,
                        'tipo' => $request->tipo,
                        'dados' => json_encode( $elementos),
                    ]
                );               

            }

        }catch(\Exception $e){

            return redirect('configuracao/ticket/campo_adicional/departamento/'.$request->departamento_id);
        }

        return redirect('configuracao/ticket/campo_adicional/departamento/'.$request->departamento_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){

        $this->autorizacao('CONFIGURACAO_TICKET_CAMPOS_EDITAR');        
        
        try{                 

            $string = new Str;

            if( $request->tipo == 'TEXTO LONGO' || $request->tipo == 'TEXTO' ){

                if( $request->tipo == 'TEXTO LONGO'){
                    $padrao = $string->upper($request->texto_longo);
                }else if( $request->tipo == 'TEXTO'){
                    $padrao = $string->upper($request->texto);
                }
                
                $campo = $this->ticketCampoRepository->update( 
                    [ 
                        'nome' => $string->upper( $request->nome ),
                        'descricao' => $string->upper($request->descricao),
                        'visivel' => $request->visivel,
                        'obrigatorio' => $request->obrigatorio,                    
                        'padrao' => $padrao,
                        'tipo' => $request->tipo,
                        'dados' => 'null',
                    ], $request->campo_id
                );

            }else{                

                $padrao=null;
                if( $request->lista != null ){

                    $i=0;
                    $elementos=json_decode( $request->lista);
                    foreach ( $elementos as $lista) {                        

                        $elementos[$i]->valor= $string->upper( $request->lista_texto_editar[$i] );
                                                                        
                        if( $lista->padrao == 'true' ){                            
                            $padrao = $lista->id;
                        } 
                        $i++;                                       
                    }                    

                }else{
                    
                    $elementos = json_decode('[{"id":0,"padrao":"false","valor":""}]');
                    $padrao=null;
                }                    
                
                $campo = $this->ticketCampoRepository->update( 
                    [ 
                        'nome' => $string->upper( $request->nome ),
                        'descricao' => $string->upper($request->descricao),
                        'visivel' => $request->visivel,
                        'obrigatorio' => $request->obrigatorio,
                        'departamento_id' => $request->departamento_id,                    
                        'padrao' => $padrao,
                        'tipo' => $request->tipo,
                        'dados' => json_encode( $elementos),
                    ], $request->campo_id
                );                             

            }

        }catch(\Exception $e){ 

            return redirect('configuracao/ticket/campo_adicional/departamento/'.$request->departamento_id);
        }

        return redirect('configuracao/ticket/campo_adicional/departamento/'.$request->departamento_id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        $this->autorizacao('CONFIGURACAO_TICKET_CAMPOS_EXCLUIR'); 
        try{

            $this->ticketCampoRepository->destroy( $id );
            return true;

        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);

        }
    }

    /**
     * Metodo usado para popular os campos adicionai na index
     */
    protected function getTicketCampo($id){

        return $this->ticketCampoRepository->scopeQuery(function($query){
            return $query->orderBy('created_at', 'asc');
        })->findWhere([ ['departamento_id', '=', $id], ['ativo', '=', true] ], ['id','nome', 'descricao', 'padrao', 'visivel', 'obrigatorio', 'tipo', 'dados']);
    }

    public function getCamposPadroes($id){

        $this->autorizacao('CONFIGURACAO_TICKET_CAMPOS_VISUALIZAR'); 

        $departamentos = $this->getDepartamentos();

        $departamentos_selected = $this->departamentoRepository->scopeQuery(function($query){
            return $query->orderBy('nome', 'asc');
        })->findWhere([ ['ticket', '=', true], ['ativo', '=', true], ['id', '=', $id] ], ['id', 'nome']);

        //fazer a querie para buscar na tabela ticket-campos
        $campos = $this->getTicketCampo($id);        

        foreach ($campos as $campo) {

            switch ($campo->tipo) {
                case 'LISTA':
                   
                    $campo->__set("template", 'LISTA' );
                    break;

                case 'TEXTO LONGO':
                    
                    $campo->__set("template", 'TEXTO LONGO' );
                    break;

                case 'TEXTO':
                    
                    $campo->__set("template", 'TEXTO' );
                    break;
                
                default:
                    break;
            }
        }        

        try{

            //$id : departamento_id
            $status = $this->getTicketStatus($id);
            $prioridades = $this->getTicketPrioridade($id);             

        }catch(\Exception $e){
            
            return $this->index();            
        }

        return view( 'configuracao.ticket.campo_adicional.index', [ 
            'departamentos' => $departamentos,
            'departamentos_selected' => $departamentos_selected,
            'campos_adicionais' => $campos,
            'status' => $status,
            'prioridades' => $prioridades,            
            'migalhas' => $this->migalhaDePao( 'CONFIGURAR' ),
            'page' => $this->page,
            'departamento_id' => $departamentos_selected[0]->id
        ] );

    }    

    /**
     * Metodo utilizado para retornar e popular o modal de editar campos adicionais
     */
    public function getCampoAdicional($id){
                
        $campo = $this->ticketCampoRepository->findWhere([ ['id', '=', $id],['ativo', '=', true] ], ['id', 'nome', 'descricao', 'padrao', 'visivel', 'obrigatorio', 'tipo', 'dados', 'departamento_id']);
        
        return $campo;
    }

    //DEPARTAMENTO
    private function getDepartamentos(){

        return $this->departamentoRepository->scopeQuery(function($query){
            return $query->orderBy('nome', 'asc');
        })->findWhere([ ['ticket', '=', true], ['ativo', '=', true] ], ['id', 'nome']);
    }

    //STATUS    
    protected function getTicketStatus($id){

        $status = $this->ticketStatusRepository->scopeQuery(function($query){
                return $query->orderBy('ordem', 'asc');
            })->findWhere([ ['ativo', '=', true], ['departamento_id', '=', $id] ], ['id', 'nome', 'cor', 'departamento_id', 'aberto', 'descricao', 'ordem']);
        
        return $status;
    }

    public function storeStatus(CampoAdicionalStatusRequest $request){

        $aberto = false;
        if (array_key_exists("aberto",$request->all())) {
            $aberto = true;
        }

        try{

            $string = new Str;

            $status = $this->ticketStatusRepository->create([ 
                    
                    'nome' => $string->upper($request->nome),
                    'descricao' => $request->descricao,
                    'cor' => $request->cor,
                    'aberto' => $aberto,
                    'departamento_id' => $request->departamento_id,
                    'ativo' => true,
                    'ordem' => $request->ordem
                ]
            );

        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgStore( $this->entidadeStatus ) ] ,404);
        }

        return ['status'=>true, 'mensagem' => $this->success->msgStore( $this->entidadeStatus ) ];
        
    }

    public function destroyStatus($id){

        try{

            $this->ticketStatusRepository->destroy( $id );
            return $id;
            
        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    { 
        try{          

            $string = new Str;                

            $departamento_id = null;
            for ($i=0; $i < count($request->nome); $i++) {

                try{

                    $validator = \Validator::make(['nome' => array( $request->nome[$i] )], [
                        'nome' => 'required|max:255|'
                        .Rule::unique('tickets_status')->ignore($request->status_id[$i])                        
                    ]);

                    $this->ticketStatusRepository->update( 
                        [ 
                            'nome' => $string->upper( $request->nome[$i] ),
                            'descricao' => $string->upper( $request->descricao[$i] ),
                            'cor' => $request->cor[$i],
                            'ordem' => $request->ordem[$i],
                            'aberto' => $request->aberto[$i]
                        ], $request->status_id[$i]
                    );

                }catch(\Exception $e){

                    return \Response::json(['errors' => "Não foi possível editar Campo status de nome " . $request->nome[$i] ] ,404);
                }
                $departamento_id = $request->departamento_id[$i];

            }
        
        }catch(\Exception $e){
            
            return redirect('configuracao/ticket/campo_adicional/departamento/'.$departamento_id);
        }
        
        return redirect('configuracao/ticket/campo_adicional/departamento/'.$departamento_id);
       
    }

    //PRIORIDADE
    protected function getTicketPrioridade( $id ){
       
        $prioridade = $this->ticketPrioridadeRepository->scopeQuery(function($query){                
            return $query->orderBy('prioridade', 'asc');
        })->findWhere([ ['ativo', '=', true], ['departamento_id', '=', $id] ], ['id', 'nome', 'cor', 'departamento_id', 'prioridade', 'descricao']);
                
        return $prioridade;
    }    

    public function storePrioridade(Request $request){

        try{

            $string = new Str;                  
            $prioridade = $this->ticketPrioridadeRepository->create([ 
                    
                    'nome' => $string->upper($request->nome),
                    'cor' => $request->cor,
                    'departamento_id' => $request->departamento_id,
                    'prioridade' => $request->prioridade,
                    'ativo' => true,
                    'descricao' => $this->strToUpperCustom($request->descricao)
                ] 
            );            

        }catch(\Exception $e){

            dd($e->getMessage());

            return \Response::json(['errors' => $this->errors->msgStore( $this->entidade ) ] ,404);
        }

        return ['status'=>true, 'mensagem' => $this->success->msgStore( $this->entidade ), 'id' => $prioridade->id ];
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePrioridade(Request $request)
    {       

        try{

            $string = new Str;
            $departamento_id=null;

            for ($i=0; $i < count($request->nome); $i++) {

                try{

                    $validator = \Validator::make(['nome' => array( $request->nome[$i] )], [
                        'nome' => 'required|max:255|'
                        .Rule::unique('tickets_prioridade')->ignore($request->prioridade_id[$i])                        
                    ]);

                    $departamento_id = $request->departamento_id[$i];

                    $this->ticketPrioridadeRepository->update( 
                        [ 
                            'nome' => $string->upper( $request->nome[$i] ),
                            'cor' => $request->cor[$i],
                            'prioridade' => $request->prioridade[$i],
                            'descricao' => $this->strToUpperCustom($request->descricao[$i])
                        ], $request->prioridade_id[$i]
                    );

                }catch(\Exception $e){

                    return \Response::json(['errors' => "Não foi possível editar Campo prioridade de nome " . $request->nome[$i] ] ,404);
                }

            }
        
        }catch(\Exception $e){
            
            return redirect('configuracao/ticket/campo_adicional/departamento/'.$departamento_id);
        }

        return redirect('configuracao/ticket/campo_adicional/departamento/'.$departamento_id);
        
    }
    
    public function destroyPrioridade($id){

        try{

            $this->ticketPrioridadeRepository->destroy( $id );
            return $id;

        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);

        }
    }

}