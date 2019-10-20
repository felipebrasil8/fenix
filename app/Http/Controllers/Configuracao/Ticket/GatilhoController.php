<?php

namespace App\Http\Controllers\Configuracao\Ticket;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Configuracao\Usuario;
use App\Models\RH\Departamento;
use App\Models\RH\Cargos;
use App\Models\Configuracao\Ticket\Gatilho;
use App\Models\Ticket\TicketStatus;



use Illuminate\Support\Str;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

class GatilhoController extends Controller
{

    private $errors;
    private $success;

    public function __construct( Errors $errors,
                    Success $success){
        $this->errors = $errors;
        $this->success = $success;
       
        $this->departamentos = Departamento::where('ativo', '=', true)
                                ->where('ticket', '=', true)
                                ->select('id', 'nome')
                                ->orderBy('nome')->get();

        $this->page = "gatilho";
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->autorizacao('CONFIGURACAO_TICKET_GATILHOS_VISUALIZAR');        
        
        return view( 'configuracao.ticket.gatilho.index', [ 
            'departamentos' => $this->departamentos,
            'departamento' => 'false',
            'responsaveis' => 'false',
            'cargos' => 'false',
            'usuarios' => 'false',
            'status' => 'false',
            'todosdep' => 'false',
            'gatilhos' => 'false',
            'edit' => false,
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
    public function store(Request $request)
    {
        //dd($request);
        $this->autorizacao('CONFIGURACAO_TICKET_GATILHOS_CADASTRAR');        

        $ordem = Gatilho::where('ativo', '=', true)
                    ->where('departamento_id', '=', $request->departamento_id)
                     ->max('ordem');

        $nome = Gatilho::where('nome', '=', strtoupper($request->nome))
                ->where('departamento_id', '=', $request->departamento_id)
                ->count();

           
        if ( $nome == 0 ) {

            // QUANDO EXECUTA  =  NOTIFICACAO              
            if ( $request->executar == 'not' ){

                $quanto_executar = [
                    'dt_notificacao' => 'mudou'
                ];
                
                $acao =[
                    'notificacao' => [
                       'mensagem' => $request->mesagemnot
                    ]    
                ];


             // QUANDO EXECUTA  =  RESPONSAVEL              
            } else {

                if ( $request->executar == 'res' ) {
                    $quanto_executar = ['responsavel' => $request->responsavel];

                } else if ( $request->executar == 'status' ) {
                    $quanto_executar = ['status' => $request->status];

                }

                $acao = $this->montaAcaoGatilho($request);
            
            };
            
            $nome = $this->strToUpperCustom($request->nome);
            $descricao = $this->strToUpperCustom($request->descricao);
           
            Gatilho::insertGetId([

                'nome' => strtoupper($nome),
                'departamento_id' => $request->departamento_id,
                'usuario_inclusao_id' => \Auth()->user()->id, 
                'ordem' =>  $ordem + 10,
                'ativo' => true,
               
                'quanto_executar' => json_encode($quanto_executar),
                'acao' => json_encode($acao),
                'descricao' =>   $descricao,

            ]);
            
        }
                

       
        return redirect()->to('/configuracao/ticket/gatilho/departamento/'.$request->departamento_id)->send();
  
    }



    private function montaAcaoGatilho ($acaoGatilho) {
      //  dd(is_null($acaoGatilho->acao));
    if ( is_null($acaoGatilho->acao) ){
            redirect()->to('/configuracao/ticket/gatilho/departamento/'.$acaoGatilho->departamento_id)->send();
            exit;
       }

            if (  $acaoGatilho->acao ==  'data' ){
                    $acao = [$acaoGatilho->dataCampo => $acaoGatilho->valor,];   
      

                } elseif (  $acaoGatilho->acao == 'respon'){

                    $acao = [
                        'responsavel' => $acaoGatilho->acaoResposavel,
                    ];  

                } elseif (  $acaoGatilho->acao == 'notif' ){
                    
                    $solicitante = false;
                    $responsavel = false;
                    $departamento = false;
                    $cargo = false;
                    $usuario = false;
                    $mensagem = false;

                    if( $acaoGatilho->responsavel == "on" ) {
                        $responsavel = true;
                    }
                    if( $acaoGatilho->solicitane == "on" ) {
                        $solicitante = true;
                    }
                    if( $acaoGatilho->departamento == "on" ) {
                      
                        $departamento = array();
                        foreach ($acaoGatilho->listaDepartamento as $dep) {
                             $departamento["d$dep"] = intval($dep);
                        };

                      //  $departamento = $data;
                    }
                    if( $acaoGatilho->cargo == "on" ) {
                  
                        $cargo = array();
                        foreach ($acaoGatilho->listaCargo as $car) {
                             $cargo["c$car"] = intval("$car");
                        };
                      //  $cargo = $data;

                       
                    }
                    if( $acaoGatilho->usuario == "on" ) {
                        
                        $usuario = array();
                        foreach ($acaoGatilho->listaUsuarios as $use) {
                             $usuario["u$use"] = intval($use);
                        };
                       // $usuario = $data;

                    }

                     $acao = [
                    'notificacao' => [
                        'solicitante' => $solicitante,
                        'responsavel' => $responsavel,
                        'departamento' => $departamento,
                        'cargo' => $cargo,
                        'usuario' => $usuario ,
                        'mensagem' => $acaoGatilho->mensagem
                        ]    
                    ];
                  

            };
       
    
         return $acao;
        
                    
     }


    private function montaArrayGatilho ($acao) {

        $newArray = array();

        foreach ($acao as $item) {

         $newArray["".$item.""] = $item; 
        
        }

        return $newArray;


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
        $this->autorizacao('CONFIGURACAO_TICKET_GATILHOS_VISUALIZAR'); 

        $gatilhos = Gatilho::where('ativo', '=', true)
                                ->where('departamento_id', '=', $id)
                                ->select('id', 'nome', 'quanto_executar', 'acao', 'descricao' )
                                ->orderBy('ordem')->get();
            
        $cargos = Cargos::where('ativo', '=', true)
                                ->select('id', 'nome')
                                ->orderBy('nome')->get();


        $usuarios = Usuario::where('ativo', '=', true)
                                ->select('id', 'nome')
                                ->orderBy('nome')->get();

        $status = TicketStatus::where('ativo', '=', true)
                                ->where('departamento_id', '=', $id)
                                ->select('id', 'nome')
                                ->orderBy('ordem')->get();
        
        $todosdep = Departamento::where('ativo', '=', true)
                                ->select('id', 'nome')
                                ->orderBy('nome')->get();

        $responsaveis = $this->getTicketResponsavelEdit($id);

        return view( 'configuracao.ticket.gatilho.index', [ 
            'departamentos' => $this->departamentos,
            'departamento' => $id,
            'responsaveis' => $responsaveis,
            'cargos' => $cargos,
            'usuarios' => $usuarios,
            'status' => $status,
            'gatilhos' => $gatilhos,
            'todosdep' => $todosdep,
            'edit' => true,
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
        $this->autorizacao('CONFIGURACAO_TICKET_GATILHOS_EDITAR');        
        if( $request->id != $request->ticket_categoria_id){

        try
        {
           
            $nome = Gatilho::where('id', '<>', $id)
                            ->where('nome', '=', strtoupper($request->nome))
                            ->where('departamento_id', '=', $request->departamento_id)
                            ->count();

           
            if ( $nome == 0 ) {
                // QUANDO EXECUTA  =  NOTIFICACAO              
                if ( $request->executar == 'not' ){

                    $quanto_executar = [
                        'dt_notificacao' => 'mudou'
                    ];
                    
                    $acao =[
                        'notificacao' => [
                            'mensagem' => $request->mesagemnot
                        ]    
                    ];


                 // QUANDO EXECUTA  =  RESPONSAVEL              
                } else {

                    if ( $request->executar == 'res' ) {
                        $quanto_executar = [
                            'responsavel' => $request->responsavel
                        ];
                    } else if ( $request->executar == 'status' ) {
                        $quanto_executar = [
                            'status' => $request->status
                        ];
                    }

                     $acao = $this->montaAcaoGatilho($request);
                };
                
                $nome = $this->strToUpperCustom($request->nome);
                $descricao = $this->strToUpperCustom($request->descricao);

                Gatilho::where('id', $id )
                    ->update([
                        'nome' =>  strtoupper($nome),
                        'usuario_alteracao_id' => \Auth()->user()->id, 
                        'quanto_executar' => json_encode($quanto_executar),
                        'acao' => json_encode($acao),
                        'descricao' =>   $descricao,
                ]);

            }
        } catch(\Exception $e)
            {   
                return redirect()->to('/configuracao/ticket/gatilho/departamento/'.$request->departamento_id)->send();
            }
            
        }
         return redirect()->to('/configuracao/ticket/gatilho/departamento/'.$request->departamento_id)->send();
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $this->autorizacao('CONFIGURACAO_TICKET_GATILHOS_EXCLUIR'); 

        try
        {
            Gatilho::where('id', $id)->delete();
            return ['status' => true];
        }
        catch(\Exception $e)
        {
            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);
        }


       
    }



    private function getTicketResponsavelEdit( $departamento_id )
    {

        // Retorna os usuários do departamento
        $responsavel = Usuario::where( 'usuarios.ativo', '=', true )
                            ->where( 'cargos.departamento_id', '=', $departamento_id )
                            ->join('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
                            ->join('cargos', 'funcionarios.cargo_id', '=', 'cargos.id')
                            ->select('usuarios.id', 'usuarios.nome','funcionarios.gestor_id')
                            ->orderBy('usuarios.nome')
                            ->get();

        // Retorna os usuários supervisores dos usuários do departamento
        foreach ($responsavel as $value) {

            $supervisor = Usuario::where( 'usuarios.ativo', '=', true )
                                ->where( 'funcionarios.id', '=', $value->gestor_id )
                                ->join('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
                                ->join('cargos', 'funcionarios.cargo_id', '=', 'cargos.id')
                                ->select('usuarios.id', 'usuarios.nome','funcionarios.gestor_id')
                                ->orderBy('usuarios.nome')
                                ->get();

            if (!empty($supervisor))
                $responsavel = $responsavel->concat($supervisor);

        }

        return $responsavel->unique()->sortBy('nome')->values();
    }

    /**
     * Metodo que atualiza Gatilho a cada movimento de drag and drop do usuario em gatilhos
     * @param Object Request
     * @return JSON
     */
    public function storeMultiplo(Request $request){

        try{

            $soma = 1;
            for ($i=0; $i < count($request->all()); $i++) {             

                $gatilho = Gatilho::find($request[$i]['id']);

                $gatilho->nome = $request[$i]['nome'];                
                $gatilho->ordem = $soma*10;
                $gatilho->save();

                $soma++;
            }

        }catch(\Exception $e){
            
            return response()->json(['error' => 'Não foi possível atualizar gatilhos'], 401);
        }

        return response()->json(['success' => 'Gatilhos atualizados com sucesso'], 200);
    }
}
