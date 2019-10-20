<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cookie;
use App\Models\RH\Funcionario;
use App\Models\RH\Departamento;
use App\Models\Core\PermissaoUsuario;
use App\Models\Ticket\TicketView;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketStatus as Status;
use App\Models\Ticket\TicketPrioridade as Prioridade;
use App\Models\Ticket\TicketCategoria as Categoria;
use App\Models\Configuracao\Usuario;
use App\Models\Configuracao\Sistema\Parametro;
use Carbon\Carbon;

use App\Http\Controllers\Core\ExcelController;
use Excel;

class TicketDashboardController extends Controller
{
    use AuthenticatesUsers;

    private $ticket;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->ticketView = new TicketView();   
        $this->ticket = new Ticket();   
        $this->usuario = new Usuario();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function index()
    {
        $this->autorizacao('TICKET_VISUALIZAR_DASHBOARD');

        $funcionario = new Funcionario;
        $departamentos = null;

        if( \Auth::user()->can( 'TICKETS_VISUALIZAR_DASHBOARD_TODOS_DEPARTAMENTOS') != false ){
            $visualizar_todos = true;
        }else {
            $visualizar_todos = $this->authorize( 'getPermissaoTicketVisualizarIndex', Ticket::class )->message();
            
        }

        // Verifica a permissao de visualizar todos
        if ( $visualizar_todos )
        {
            $departamentos = Departamento::where('ativo', '=', true)->where('ticket', '=', true)->select('id', 'nome')->orderBy('nome')->get();
        }

        $departamento = $this->usuario->getDepartamento( auth()->user()->id );

        if ( !empty($departamento) )
        {
            if (!$visualizar_todos)
            {
                $departamentos = Departamento::where('ativo', '=', true)->where('ticket', '=', true)->where('id', '=', $departamento->departamento_id )->select('id', 'nome')->orderBy('nome')->get();
            }
            $departamentos = $departamentos->filter(function ($value, $key) use ($departamento){
                if( $departamento->departamento_id == $value->id )
                {
                    $value->__set('selected', 'selected');
                }
                else
                {
                    $value->__set('selected', '');
                }

                return $value;
            });
        }

        $tempo = Parametro::select('valor_numero')
                ->where( [['ativo', '=', true], ['nome', '=', 'TEMPO_TIMEOUT_DASHBOARD_TICKET']])
                ->get()
                ->first()->valor_numero;
        
        return view('ticket.dashboard', [
            'funcionarios' => $funcionario->proximosAniversarios(),
            'meusTicketsAbertos' => $this->ticket->MeusTicketsAbertos( \Auth()->user()->id ),
            'departamentos' => $departamentos,
            'visualizar_todos' => $visualizar_todos,
            'migalhas' => $this->migalhaDePao(),
            'timeout' => $tempo
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  void
     * @return \Illuminate\Http\Response
     */
    public function show($departamento_id, Request $request)
    {

        // Tickets por responsável, tickets abertos
        $responsaveisAbertos = $this->getResponsaveisAbertosSemPeriodo( $departamento_id, $request );
        
        // Tickets por responsável, tickets fechados
        $responsaveisFechados = $this->getResponsaveisFechadosComPeriodo( $departamento_id, $request );
        
        $pessoas = $new_abertos = $new_fechados = array();
        $cores = $this->responsaveisCor();
        $c=0;
        for ($i=0; $i < count($responsaveisAbertos); $i++) { 

            if( $responsaveisAbertos[$i]['total'] == 0 && $responsaveisFechados[$i]['total'] == 0){
                //nda
            } else {    

                if($responsaveisAbertos[$i]['total'] != 0){                    
                    array_push( $new_abertos, 
                        array( 'id'=>$responsaveisAbertos[$i]['id'], 
                               'nome' => $responsaveisAbertos[$i]['nome'], 
                               'cor'=>$cores[$c], 
                               'total'=>$responsaveisAbertos[$i]['total'] ) );
                }
                
                if($responsaveisFechados[$i]['total'] != 0){                
                    array_push( $new_fechados, 
                        array( 'id'=>$responsaveisFechados[$i]['id'], 
                               'nome' => $responsaveisFechados[$i]['nome'], 
                               'cor'=>$cores[$c], 
                               'total'=>$responsaveisFechados[$i]['total'] ) );
                }
                $url = '/configuracao/usuario/avatar-pequeno/'.$responsaveisFechados[$i]['avatar'];
                array_push( $pessoas, 
                        array( 'nome' => $responsaveisFechados[$i]['nome'], 'cor'=>$cores[$c], 'url'=>$url ) );
                $c++;

            }
        }
        
        //grafico: Tickets por status abertos no período
        $status = $this->getAbertosPorStatus( $departamento_id, $request )->toArray();

        //grafico: Tickets abertos por prioridade
        $prioridades = $this->getAbertosPorPrioridade( $departamento_id, $request )->toArray();

        //************Dados Simples************

        //tickets novos
        $ticketsNovos = $this->ticketsNovos($departamento_id);

        //total de tickets abertos
        $totalDeTicketsAbertos = $this->totalDeTicketsAbertos($departamento_id);

        //tickets fechados no periodo
        $ticketsFechadosNoPeriodo = $this->ticketsFechadosNoPeriodo($departamento_id, $request);

        //avaliacao
        $avaliacao = $this->avaliacao($departamento_id, $request);
        $avaliacaoMedia = $avaliacao['media'];
        $avaliacaoQtde = $avaliacao['qtde'];

        //tempo medio de atendimento
        $tempoMedioDeAtendimento = $this->tempoMedioDeAtendimento($departamento_id, $request);

        //tickets por categoria
        $ticketsPorCategoria = $this->getTicketCategoria($departamento_id, $request)->values()->all();

        //tickets por departamento
        $ticketsDepartamento = $this->ticketsDepartamento($departamento_id);
        
        return \Response::json( [
            'responsaveis' => $pessoas, 
            'responsaveisAbertos' => $new_abertos, 
            'responsaveisFechados' => $new_fechados, 
            'status' => $status, 
            'prioridades' => $prioridades, 
            'ticketsNovos'=> $ticketsNovos, 
            'totalDeTicketsAbertos'=> $totalDeTicketsAbertos, 
            'ticketsFechadosNoPeriodo'=> $ticketsFechadosNoPeriodo, 
            'avaliacao'=>$avaliacaoMedia, 
            'avaliacaoQtde'=> $avaliacaoQtde, 
            'tempoMedioDeAtendimento'=> $tempoMedioDeAtendimento, 
            'ticketsPorCategoria' => $ticketsPorCategoria, 
            'ticketsAbertosPorDepartamento' => $ticketsDepartamento 
        ] , 200);
    }    

    private function getTicketResponsavel( $departamento_id )
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

        $resp = array();
        foreach ($responsavel as $value) {
            array_push($resp, $value->id );
        }

        $resp = array_unique( $resp );        

        return $resp;
    }

    private function getResponsaveisAbertosSemPeriodo( $departamento_id, $request ){

        $responsaveis = $this->getTicketResponsavel( $departamento_id );

        $resp = Usuario::selectRaw('usuarios.id, usuarios.nome, count(tb.id) as total, funcionarios.avatar')
            ->leftjoin( \DB::raw('
                    (select 
                        tickets.id, 
                        tickets.usuario_responsavel_id
                    from tickets            
                    left join tickets_status on tickets.tickets_status_id = tickets_status.id and  tickets_status.departamento_id = '. $departamento_id .'
                    where tickets_status.aberto = TRUE ) as tb '), 'usuarios.id',  '=',  'tb.usuario_responsavel_id' )            
            ->leftjoin('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
            ->where(function($query) use ( $responsaveis ) {
                $query->whereIn('usuarios.id', $responsaveis);  
            })            
            ->groupBy('usuarios.id', 'usuarios.nome', 'funcionarios.avatar')
            ->orderBy('usuarios.nome', 'asc')
            ->get();

        return $resp;        
    }    

    private function responsaveisCor(){

        $colors = $this->colorsTicketResponsaveis();    
    
        return $colors;
    }


    private function getResponsaveisFechadosComPeriodo( $departamento_id, $request ){

        $responsaveis = $this->getTicketResponsavel( $departamento_id );

        $resp = Usuario::selectRaw('usuarios.id, usuarios.nome, count(tb.id) as total, funcionarios.avatar')
            ->leftjoin( \DB::raw('
                    (select 
                        tickets.id, 
                        tickets.usuario_responsavel_id
                    from tickets            
                    left join tickets_status on tickets.tickets_status_id = tickets_status.id and  tickets_status.departamento_id = ' . $departamento_id . ' 
                    where tickets_status.aberto = FALSE AND '. $this->dateFilter($request, 'dt_fechamento') .' ) as tb '), 'usuarios.id',  '=',  'tb.usuario_responsavel_id' )            
            ->leftjoin('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
            ->where(function($query) use ( $responsaveis ) {
                $query->whereIn('usuarios.id', $responsaveis);  
            })            
            ->groupBy('usuarios.id', 'usuarios.nome', 'funcionarios.avatar')
            ->orderBy('usuarios.nome', 'asc')
            ->get();

        return $resp;
    }

    private function colorsTicketResponsaveis(){
        return [ '#59D6F4', '#59C593', '#f39c12', '#7EDEDE', '#00c0ef', '#00a65a', '#ff851b', '#39CCCC', '#3c8dbc', '#80B5D3'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Este metodo faz a conta pra mostrar a dta referente ( ex:se for ate 59 minutos, se for mais de uma hora em horas )
     */
    private function dataHistorico( $created_at ){        
        
        $date1 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create($created_at), 'd/m/Y H:i:s'));
        $date2 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create(Carbon::now('America/Sao_Paulo')), 'd/m/Y H:i:s'));

        if( $date2->diffInSeconds($date1) <= 59 )
        {
            $valor = $date2->diffInSeconds($date1).' segundos';
        }
        else if( $date2->diffInMinutes($date1) <= 1 )
        {
            $valor = $date2->diffInMinutes($date1).' min';
        }
        else if( $date2->diffInMinutes($date1) <= 59 )
        {
            $valor = $date2->diffInMinutes($date1).' mins';
        }
        else if( $date2->diffInHours($date1) == 1 )
        {
            $valor = '1 hora';   
        }
        else if( $date2->diffInHours($date1) <= 23 )
        {
            $valor = $date2->diffInHours($date1).' horas';   
        }
        else if( $date2->diffInDays($date1) == 1 )
        {
            $valor = '1 dia';   
        }
        else
        {
            $valor = $date2->diffInDays($date1).' dias';  
        }

        return $valor;        

    }

    private function ticketsDepartamento( $departamento_id )
    {
        $ticketsDepartamento = $this->ticketView->TicketsAbertosDepartamento( [true], $departamento_id );

        $ticketsDepartamento = $ticketsDepartamento->filter(function ($value, $key) {
            $value->tempo_interacao = $this->dataHistorico( $value->created_at);
            $value->criado = date_format(date_create( $value->created_at ), 'd/m/Y H:i:s');
                      
            return $value['status_aberto'] ;
        });

        return $ticketsDepartamento;
    }

    private function dateFilter( $request, $column = 'tickets.created_at' )
    {
       
        if( $request->data == 'hoje' )
        {
            return "TO_CHAR(".$column.", 'DD-MM-YYYY') = TO_CHAR(NOW(), 'DD-MM-YYYY')";
        }
        else if( $request->data == 'mes_atual' )
        {
            return "TO_CHAR(".$column.", 'MM-YYYY') = TO_CHAR(NOW(), 'MM-YYYY')";
        }
        else if( $request->data == 'ultimos_trinta_dias' )
        {
            return "".$column."::DATE >= (NOW() - '1 month'::INTERVAL)::DATE";
        }
        else if( $request->data == 'ano_atual' )
        {
            return "TO_CHAR(".$column.", 'YYYY') = TO_CHAR(NOW(), 'YYYY')";
        }
        else if( $request->data == 'ultimo_ano' )
        {
            return "".$column."::DATE >= (NOW() - '1 year'::INTERVAL)::DATE";
        }
        else if( $request->data == 'customizado' )
        {
            if( isset($request->data_de) && isset($request->data_ate) )
            {
                return "".$column."::DATE BETWEEN '".$request->data_de."'::DATE AND '".$request->data_ate."'::DATE";
            }
        }

        return "FALSE";
    }

    private function getAbertosPorStatus( $departamento_id, $request ){

        return Status::where( 'tickets_status.departamento_id', '=', $departamento_id )
            ->where( 'tickets_status.aberto', '=', true )            
            ->join('tickets', 'tickets_status.id', '=', 'tickets.tickets_status_id')            
            ->selectRaw('count(tickets.id) as total, tickets_status.nome, tickets_status.cor')
            ->groupBy('tickets_status.id', 'tickets_status.nome')            
            ->orderBy('tickets_status.ordem')
            ->get();
    }

    private function getAbertosPorPrioridade( $departamento_id, $request ){

        return Prioridade::where( 'tickets_prioridade.departamento_id', '=', $departamento_id )            
            ->leftjoin('tickets', 'tickets_prioridade.id', '=', 'tickets.tickets_prioridade_id')
            ->leftjoin('tickets_status', 'tickets_status.id', '=', 'tickets.tickets_status_id')
            ->where( 'tickets_status.aberto', '=', true )            
            ->selectRaw('count(tickets.id) as total, tickets_prioridade.nome, tickets_prioridade.cor')
            ->groupBy('tickets_prioridade.id', 'tickets_prioridade.nome')            
            ->get();
    }

    /** 
     * Gráfico: Tickets por categoria fechados no período
     */
    private function getTicketCategoria( $departamento_id, $request )
    {
        $tickets = TicketView::where( 'departamento_id', '=', $departamento_id )
            ->whereRaw( $this->dateFilter($request, 'dt_fechamento') )
            ->select('categoria_id_pai AS id', 'categoria_nome_pai AS nome')
            ->orderBy('categoria_nome_pai')
            ->get();

        $tickets = $tickets->mapToGroups(function ($item, $key)
        {
            return [$item['id'] => $item];
        });

        $categorias = Categoria::whereNull('ticket_categoria_id')->where('departamento_id', '=', $departamento_id)->select('id', 'nome')->orderBy('nome')->get();

        $categorias = $categorias->map(function ($item, $key) use ($tickets) {
            $item->total = 0;
            if( isset($tickets[$item->id]) )
            {
                $item->total = count($tickets[$item->id]);
            }
            return $item;
        });

        return $categorias->sortByDesc('total');
    }

    /**
     * Tickets novos: quantidade de tickets com status aberto e que não possuam responsável
     * @param int $departamento_id
     * @return int
     */
    private function ticketsNovos( $departamento_id ){

        return $this->ticket->getQtdeTicketsNovosSemResponsavel( $departamento_id );        
    }

    /**
     * Total de tickets abertos: quantidade de tickets com status aberto 
     * @param int $departamento_id
     * @return int
     */
    private function totalDeTicketsAbertos( $departamento_id ){

        return $this->ticket->getQtdeTicketsAbertos( $departamento_id );
    }

    /**
     * Tickets fechados no período: quantidade de tickets com status não aberto que possuem data de fechamento dentro do período filtrado 
     * @param int $departamento_id
     * @return int
     */
    private function ticketsFechadosNoPeriodo( $departamento_id, $request ){

        return Ticket::where( 'tickets_status.aberto', '=', false )
          ->where('tickets.departamento_id', '=', $departamento_id)
        ->whereRaw( $this->dateFilter( $request, 'dt_fechamento' ) )
        ->leftjoin('tickets_status', 'tickets_status.id', '=', 'tickets.tickets_status_id')
        ->select('*')
        ->count();
    }

    private function avaliacao($departamento_id, $request){

        $avaliacao = $this->getTicketAvaliacao( $departamento_id, $request);
        $media = '-'; 
        $qtde = 0;
        
        if( !$avaliacao->isEmpty() ){

            $avaliacao = $avaliacao->filter(function ($value, $key) {
                return $value->avaliacao != null;
            });
            if($avaliacao->sum('total') != 0){
                $media = number_format(($avaliacao->sum('nota')/$avaliacao->sum('total')), 2,',','');
                $qtde = $avaliacao->sum('total');
            }
        }
      
        return [ 'media' => $media, 'qtde' => $qtde ];
    }

    private function  tempoMedioDeAtendimento($departamento_id, $request){

        $resolucao = $this->getTicketTempoMedio( $departamento_id, $request);
       
        return $this->dataAtendimento($resolucao);

        
    }

    /**
    * EXCEL EXPORTAR
    *
    */
    public function downloadExcel( Request $request, $grafico ,ExcelController $excel, $type = 'xlsx' ) { 

        $this->autorizacao( 'TICKETS_EXPORTAR' );
        $formato = array();
       // $filter_permissao = $this->autorizacaoTicket( 'getPermissaoTicketVisualizarIndex', Ticket::class );

        if($grafico == 'responsavel') {
            
            $arquivo = 'Tickets_por_Responsavel_';

            $titulo_excel = array('Responsável','Tickets Abertos', 'Tickets Abertos (%)', 'Tickets Fechado', 'Tickets Fechado (%)');

            $dados = $this->responsavelExport( $request->departamento_id, $request );     
 
            $formato = array(
                        'C' => '0.00%',
                        'E' => '0.00%'
                    );
        } else if ( $grafico == 'status' ) {
           
            $arquivo = 'Tickets_por_Status_';
            $titulo_excel = array('Status','Tickets Abertos', 'Tickets Abertos (%)');
             //Status
            $status = $this->getAbertosPorStatus(  $request->departamento_id, $request );

            $dados = $this-> calculaPorcentagem($status);

            $formato = array(
                        'C' => '0.00%',
                 );  
     

        } else if ( $grafico == 'prioridade' ) {
            $arquivo = 'Tickets_por_Prioridade_';
            $titulo_excel = array('Priotidade','Tickets Abertos', 'Tickets Abertos (%)');
            //Prioridade
            $prioridade = $this->getAbertosPorPrioridade(  $request->departamento_id, $request );
            
            $dados = $this-> calculaPorcentagem($prioridade);

            $formato = array(
                        'C' => '0.00%',
                 ); 

        } else if ( $grafico == 'categoria' ) {
            $arquivo = 'Tickets_por_Categoria_';
            $titulo_excel = array('Categoria','Tickets Fechados', 'Tickets Fechados (%)');
           
            //Categoria
            $ticketsPorCategoria = $this->getTicketCategoria( $request->departamento_id, $request); 
          
            $dados = $this->calculaPorcentagem($ticketsPorCategoria);

            $formato = array(
                        'C' => '0.00%',
                 ); 


        } else if ( $grafico == 'avaliacao' ) {

            $arquivo = 'Tickets_Avaliacao';

            $titulo_excel = array('Avaliação','Votos', 'Votos (%)');
           
            //Categoria
            $ticketsPorCategoria = $this->getTicketAvaliacao( $request->departamento_id, $request); 
          
            $dados = $this->calculaPorcentagem($ticketsPorCategoria);

            $formato = array(
                        'C' => '0.00%',
                 ); 


        }

  
            
        return $excel->downloadExcel($type, $dados, $arquivo, $titulo_excel, $formato);

    }   


    private function calculaPorcentagem($collection){

        $total = $collection->sum('total');
        $dados = array();
        if( $total > 0 ){
            $dados = array();   
            for ($i=0; $i < count($collection); $i++) { 
                 $perStatus = '0';
                 if ( $collection[$i]['total'] != 0 ) {
                        $perStatus = $collection[$i]['total']/$total;
                    }
                array_push( $dados, 
                    array( 'nome' => $collection[$i]['nome'], 
                               'total'=>  ''.$collection[$i]['total'].'' , 
                                    'totalP'=>   $perStatus                                  
                            ) );
            }
            
        }

        return $dados;

    }


    private function responsavelExport($departamento_id, $request) {

        // Tickets por responsável, tickets abertos
        $responsaveisAbertos = $this->getResponsaveisAbertosSemPeriodo( $departamento_id, $request );
        $totaslAberto = $responsaveisAbertos->sum('total');
       
        // Tickets por responsável, tickets fechados
        $responsaveisFechados = $this->getResponsaveisFechadosComPeriodo( $departamento_id, $request );
        $totaslFechado = $responsaveisFechados->sum('total');
         
        $dados = array();
        for ($i=0; $i < count($responsaveisAbertos); $i++) { 
            if( $responsaveisAbertos[$i]['total'] == 0 && $responsaveisFechados[$i]['total'] == 0){
            } else {    
                $perAberto = '0';
                $perFechado = '0';
                if ( $responsaveisAbertos[$i]['total'] != 0 ) {
                    $perAberto = $responsaveisAbertos[$i]['total']/$totaslAberto;
                }
                if ( $responsaveisFechados[$i]['total'] != 0 ) {
                    $perFechado = $responsaveisFechados[$i]['total']/$totaslFechado;
                }
                
                array_push( $dados, 
                    array( 'nome' => $responsaveisAbertos[$i]['nome'], 
                               'abertos'=>  ''.$responsaveisAbertos[$i]['total'].'' , 
                                    'abertosP'=>   $perAberto,
                                        'fechado'=> ''.$responsaveisFechados[$i]['total'].'',
                                            'fechadoP'=> $perFechado
                                             ) );
            }
        }
        return $dados;
    }


    private function getTicketAvaliacao( $departamento_id, $request )
    {

        $avaliacao = Ticket::where('tickets.departamento_id', '=', $departamento_id )
            ->leftjoin('tickets_status', 'tickets_status.id', '=', 'tickets.tickets_status_id')
            ->where( 'tickets_status.aberto', '=', false )        
            ->whereRaw( $this->dateFilter($request, 'tickets.created_at') )
            ->selectRaw('SUM(1) as total, 
                        (CASE WHEN tickets.avaliacao IS NULL THEN \'SEM AVALIAÇÃO\' ELSE \'NOTA \'|| tickets.avaliacao END) as nome, 
                        (count(tickets.avaliacao) * tickets.avaliacao) as nota, 
                        tickets.avaliacao ')
            ->groupBy('tickets.avaliacao')
            ->get();
        
        return $avaliacao;
    }


    private function getTicketTempoMedio( $departamento_id, $request )
    {
        $resolucao = Ticket::whereNotNull('dt_resolucao')
            ->where('departamento_id', '=', $departamento_id)
            ->whereRaw( $this->dateFilter($request, 'dt_resolucao') )
            ->selectRaw('extract(EPOCH FROM avg(dt_resolucao - created_at)) as tempo')
            ->first();
        
        return $resolucao->tempo; 
    }


    private function dataAtendimento( $segundos ){        
            
        $minutos = number_format($segundos/60, 0 ,'','');
        $horas = number_format($minutos/60, 0 ,'','');
        $dias = number_format($horas/24, 0 ,'','');


        if ( $segundos == 0 ){
            $valor = ' - ';
        }
        else if( $segundos <= 59 )
        {
            $valor = $segundos.' SEGUNDOS';
        }
        else if( $minutos == 1 )
        {
            $valor = '1 MINUTO';
        }
        else if(  $horas == 0 )
        {
            $valor =  $minutos .' MINUTOS';
        }
        else if( $horas == 1 )
        {
            $valor = '1 HORA';
        }
        else if( $dias == 0 )
        {
            $valor =  $horas .' HORAS';
        }
        else if( $dias == 1 )
        {
            $valor = '1 DIA';   
        }
        else
        {
             $valor = $dias .' DIAS';
        }

        return $valor;        

    }




}
