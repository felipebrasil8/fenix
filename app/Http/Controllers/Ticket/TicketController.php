<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Http\Requests\Ticket\TicketRequest;

use App\Repositories\Ticket\TicketRepositoryInterface;

use App\Models\Core\PermissaoUsuario;
use App\Models\Configuracao\Usuario;
use App\Models\RH\Departamento;
use App\Models\RH\Funcionario;
use App\Models\Ticket\TicketAcao;
use App\Models\Ticket\TicketPrioridade;
use App\Models\Ticket\TicketCategoria;
use App\Models\Ticket\TicketCampos;
use App\Models\Ticket\TicketStatus;
use App\Models\Ticket\TicketCampoTicket;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketHistorico;
use App\Models\Ticket\TicketView;
use App\Repositories\Ticket\TicketViewRepositoryInterface;
use App\Http\Controllers\Core\ExcelController;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use App\Http\Requests;
use Validator;
use App\Models\Ticket\TicketImagem;
use App\Http\Controllers\Core\ImagemController;
use Illuminate\Routing\Redirector;
use Carbon\Carbon;
use Excel;
use Event;
use App\Events\NotificacaoEvent;
use App\Util\FormatString;

class TicketController extends Controller
{
    protected $repository;
    protected $departamentoRepository;
    protected $repositoryTicket;

    private $entidade = 'Ticket';
    private $errors;
    private $success;

    public function __construct(
        TicketViewRepositoryInterface $repository, 
            TicketRepositoryInterface $repositoryTicket,
                Errors $errors,
                    Success $success)
    {
        $this->repository = $repository;
        $this->repositoryTicket = $repositoryTicket;
        $this->errors = $errors;
        $this->success = $success;

        $this->ticket_historico = new TicketHistorico();
    }

    public function autorizacaoTicket( $ability, $ticket, $migalha = '' )
    {
        if( empty(\Auth::user()) )
        {
            abort(401, 'Usuário não logado.');
        }

        $this->setIdentificadorMigalha( $migalha );
        
        // try para enviar abort caso não tenha autorização
        try
        {
            return $this->authorize( $ability, $ticket )->message();
        }
        catch( \Exception $e )
        {
            return abort(403);
        }      
    }

    public function index(){
        return $this->indexTicket( 'index', 'getPermissaoTicketVisualizarIndex', 'TICKETS_VISUALIZAR_DEPARTAMENTO' );
    }

    public function indexProprio(){
        return $this->indexTicket( 'indexProprio', 'getPermissaoTicketVisualizarIndexProprio', 'TICKETS_VISUALIZAR_PROPRIO' );
    }

    private function indexTicket( $method, $ability, $permissao )
    {
        $visualizar_todos = $this->autorizacaoTicket( $ability, Ticket::class, $permissao );

        $visualiza = true;    
        $usuarios = Usuario::where('ativo', '=', true)->select('id', 'nome')->orderBy('nome')->get();

        $departamento_id = $this->getDepartamentoDeFuncionarioLogado();
        
        if ($visualizar_todos || $method == 'indexProprio' )
        {
            $departamentos = Departamento::where('ativo', '=', true)->where('ticket', '=', true)->select('id', 'nome')->orderBy('nome')->get();
        }
        else
        {
            $departamentos = Departamento::where('ativo', '=', true)->where('ticket', '=', true)->where('id', '=', $departamento_id )->select('id', 'nome')->orderBy('nome')->get();
        }

        $view = 'ticket.index';  
        if ($method == 'indexProprio')
        {
            $view = 'ticket.indexProprio';
            $visualiza = false;
        }

        return view( $view , [
            'departamentos' => $departamentos,
            'visualiza' => $visualiza,
            'usuarios' => $usuarios,
            'filtro' => false,
            'migalhas' => $this->migalhaDePao( 'INDEX' )
        ] );  
    }

    public function search(){
        return $this->searchTicket( 'search', 'getPermissaoTicketVisualizarIndex', 'getPermissaoTicketPesquisar', 'TICKETS_VISUALIZAR_DEPARTAMENTO' );
    }

    public function searchProprio(){
        return $this->searchTicket( 'searchProprio', 'getPermissaoTicketVisualizarIndexProprio', 'getPermissaoTicketPesquisarProprio', 'TICKETS_VISUALIZAR_PROPRIO' );
    }

    private function searchTicket( $method, $abilityIndex, $ability, $permissao )
    {
        $this->autorizacaoTicket($abilityIndex, Ticket::class, $permissao);

        $departamento_id = $this->getDepartamentoDeFuncionarioLogado();
        $departamentos = Departamento::where('ativo', '=', true)->where('ticket', '=', true)->select('id', 'nome')->orderBy('nome')->get();
                
        $string = new Str;
        $codigo =  str_replace('#','',$string->upper(Input::get('codigo')));
        $acesso =  $string->upper(Input::get('assunto'));

        $array_filter = [ 
                        //utilizar DB::raw quando for usar uma funcao do banco
                        [\DB::raw("sem_acento(\"codigo\")"), 'LIKE', \DB::raw("sem_acento('%".$codigo."%')") ],
                        [\DB::raw("sem_acento(\"assunto\")"), 'LIKE', \DB::raw("sem_acento('%".$acesso."%')") ],
                    ];

        if( Input::get('aberto') == "true" ){
            array_push($array_filter,[ 'status_aberto', '=', true ]);
        } else if( Input::get('aberto') == false || Input::get('aberto') == "false" ) {
            array_push($array_filter,[ 'status_aberto', '=', false ]);
        }

        if( Input::get('statuse.id') != 0){
            array_push($array_filter,[ 'status_id', '=', (int)Input::get('statuse.id') ]);
        }     

        if( Input::get('categoria.id') != 0){
            array_push($array_filter,[ 'categoria_id_pai', '=', (int)Input::get('categoria.id') ]);
        }              
      
        if( Input::get('prioridade.id') != 0){
            array_push($array_filter,[ 'prioridade_id', '=', (int)Input::get('prioridade.id') ]);
        } 

        if( Input::get('departamento.id') != 0){
            array_push($array_filter,[ 'departamento_id', '=', (int)Input::get('departamento.id') ]);
        }             

        if( Input::get('usuario_solicitante.id') != 0 && !is_null(Input::get('usuario_solicitante.id'))  ){
            array_push($array_filter,[ 'usuario_solicitante_id', '=', (int)Input::get('usuario_solicitante.id') ]);
        }
       
        // if ( is_null(Input::get('usuario_solicitante.id'))){
        //     array_push($array_filter,[ 'usuario_solicitante_id', '=', \auth()->user()->id ]);
        // }
      
        if( !is_null(Input::get('de')) && Input::get('de') != ''){
            array_push($array_filter,[\DB::raw("date(created_at)"), '>=', \DB::raw("date('".Carbon::createFromFormat('d/m/Y', Input::get('de'))->format('Y-m-d')."')") ]);
        }
      
        if( !is_null(Input::get('ate')) && Input::get('ate') != ''){
            array_push($array_filter,[\DB::raw("date(created_at)"), '<=', \DB::raw("date('".Carbon::createFromFormat('d/m/Y', Input::get('ate'))->format('Y-m-d')."')") ]);
        }

        if( !is_null(Input::get('usuario_responsavel.id'))){  
            array_push($array_filter,['usuario_responsavel_id', '=', (int)Input::get('usuario_responsavel.id')]);
        }

        $filter_permissao = $this->autorizacaoTicket( $ability, [Ticket::class, $departamento_id] );
        // $filter_permissao = $this->autorizacaoTicket( 'getPermissaoTicketPesquisar', [Ticket::class, $this->getDepartamentoDeFuncionarioLogado()] );
        
        if( count( $filter_permissao ) > 0 )
        {
            array_push($array_filter, $filter_permissao);
        }        
        
        try
        {
            $string = new Str;
            $assunto = $string->upper( Input::get('assunto') );

            $tickets = $this->repository->paginacao(
                input::get('limite'), 
                input::get('to'), 
                $array_filter,
                input::get('coluna'),
                input::get('ordem'),
                ['id', 'codigo', 'usuario_solicitante_nome', 'assunto', 'categoria_nome_pai', 'funcionario_responsavel_nome', 'status_nome', 'status_cor', 'prioridade_nome', 'prioridade_cor', \DB::raw("to_char(created_at, 'dd/mm/YYYY HH24:MI:SS') AS data"), 'created_at as data_order', 'departamento_nome', 'usuario_responsavel_id', 'usuario_solicitante_id']
            );

        }
        catch(\Exception $e)
        {
            return \Response::json( ['errors' => $this->errors->msgSearch()] ,404);
        }

        return [ 
            'ticket' => $tickets,
            'codigo' => is_null(Input::get('codigo')) ? "" : Input::get('codigo'),
            'assunto' => is_null(Input::get('assunto')) ? "" : Input::get('assunto'),
            'departamento' => is_null(Input::get('departamento.id')) ? 0 : Input::get('departamento.id'),
            'usuario_responsavel' => is_null(Input::get('usuario_responsavel.id')) ? 0 : Input::get('usuario_responsavel.id'),
            'usuario_solicitante' => is_null(Input::get('usuario_solicitante.id')) ? 0 : Input::get('usuario_solicitante.id'),
            'categoria' => is_null(Input::get('categoria.id')) ? 0 : Input::get('categoria.id'),
            'statuse' => is_null(Input::get('statuse.id')) ? 0 : Input::get('statuse.id'),
            'prioridade' => is_null(Input::get('prioridade.id')) ? 0 : Input::get('prioridade.id'),
            'aberto' => Input::get('aberto')

        ];            
    }

    public function create()
    {
        /*
         * Valida se tem permissão
         */
        $this->autorizacaoTicket( 'getPermissaoTicketCadastrar', Ticket::class, 'TICKETS_CRIAR_PROPRIO' );

        $departamento = Departamento::where('ativo', '=', true)->where('ticket', '=', true)->select('id', 'nome')->orderBy('nome')->get();

        return view('ticket.cadastrar', [
            'departamento' => $departamento,                 
            'migalhas' => $this->migalhaDePao(), 
        ]);
    }

    /**
     * Esta funcao esta sendo reutilizada para retornar o departamento do funcionario logado
    */
    private function getDepartamentoDeFuncionarioLogado()
    {
        $usuario = new Usuario();
        $var = $usuario->getDepartamento( auth()->user()->id );

        return (empty($var)?null:$var->departamento_id);
    }

    public function getSolicitantes( $id )
    {
        $criar_outros = $this->autorizacaoTicket( 'getPermissaoTicketCadastrar', Ticket::class, 'TICKETS_CRIAR_PROPRIO' );

        $departamento_id = $this->getDepartamentoDeFuncionarioLogado();
        
        if( $criar_outros && $departamento_id == $id ){

            $solicitante = $this->getSelected( Usuario::where('ativo', '=', true)->select('id', 'nome')->orderBy('nome')->get(), \Auth()->user()->id);
            
        }else{
            
            $solicitante = $this->getSelected( Usuario::where('ativo', '=', true)->where('id', '=', auth()->user()->id )->select('id', 'nome')->orderBy('nome')->get(), \Auth()->user()->id);
                        
        }
        
        return $solicitante;
    }

    /**    
     * Este metodo retorna as prioridades ja com a menor como selected
     */
    public function getPrioridades( $id )
    {
        try
        {
            $this->autorizacaoTicket( 'getPermissaoTicketVisualizarIndexProprio', Ticket::class, 'TICKETS_VISUALIZAR_PROPRIO' );

            $prioridade = TicketPrioridade::where('ativo', '=', true)->where('departamento_id', '=', $id)
            ->select('id', 'nome', 'prioridade', 'descricao')->orderBy('prioridade')->get();

            return $this->getSelected($prioridade, $prioridade[0]->id);

        }catch( \Exception $e ){
            return false;
        }        
    }        

    public function getStatus( $id )
    { 
        $this->autorizacaoTicket( 'getPermissaoTicketVisualizarIndexProprio', Ticket::class, 'TICKETS_VISUALIZAR_PROPRIO' );
    
        $prioridade = TicketStatus::where('ativo', '=', true)->where('departamento_id', '=', $id) 
        ->select('id', 'nome')->orderBy('ordem')->get(); 
        
        return $prioridade; 
    } 

    public function getCategorias( $id )
    {
        try
        {
            $this->autorizacaoTicket( 'getPermissaoTicketVisualizarIndexProprio', Ticket::class, 'TICKETS_VISUALIZAR_PROPRIO' );

            $categoria = TicketCategoria::whereNull('ticket_categoria_id')->where('departamento_id', '=', $id)->select('id', 'nome', 'dicas')->orderBy('nome')->get();        
            return $categoria;
        }catch( \Exception $e ){
            return false;
        }        
    }    

    public function getCamposAdicionais( $id )
    {
        try
        {
            $this->autorizacaoTicket( 'getPermissaoTicketVisualizarIndexProprio', Ticket::class, 'TICKETS_VISUALIZAR_PROPRIO' );
            $campos = new TicketCampos();            
            $campos = $this->formataCamposAdicionaisHtml( $campos->getCamposAdicionais( $id ), 'create' );
            return $campos;
        }catch( \Exception $e ){
            return false;
        }        
    }

    public function pesquisaSubcategoria( Request $request )
    {
        $categoriaDica = TicketCategoria::where('id', '=', $request[0])->select('id', 'dicas')->orderBy('nome')->get();
        $subcategoria = TicketCategoria::where('ticket_categoria_id', '=', $request[0])->select('id', 'nome', 'dicas')->orderBy('nome')->get();
        return [ 'categoriaDica' => $categoriaDica, 'subcategoria' => $subcategoria ];
    }

    public function store(Request $request)
    {
        try
        {           
            /*
             * Valida se tem permissão
             */
            $this->autorizacaoTicket( 'getPermissaoTicketCadastrar', Ticket::class );

            try{
                $this->validaCamposAdicionaisCreate( $request );
            }catch( \Exception $e ){
                return \Response::json(['errors' => ['Os dados adicionais são obrigatórios' ]], 404);
            }

            $string = new Str;  

            $tk_status = TicketStatus::where('ativo', '=', true)->where('departamento_id', '=', $request->departamento['id'] )->orderBy('ordem')->take(1)->select('id')->get();
           
            /** 
             * Em categoria, guardamos o campos subcategoria id, pois assim sabemos a categoria
             */
            $ticket = $this->repositoryTicket->create( 
                [ 
                    'codigo'              => $this->codigoTicket(),
                    'departamento_id'     => $request->departamento['id'],
                    'usuario_solicitante_id'      => intval($request->solicitante['id']),
                    'tickets_prioridade_id'       => intval($request->prioridade['id']),
                    'assunto'                     => $string->upper($request->assunto),
                    'tickets_categoria_id'        => intval($request->subcategoria['id']),
                    'descricao'                   => $string->upper($request->descricao),
                    'tickets_status_id'           => intval($tk_status[0]->id),
                ] 
            );

            $this->adicionaTicketCampoTicket( $ticket->id, $request->campo_adicional );

            /*
             *  Salva Historico
             */    
            $this->ticket_historico->historicoTicketCreate( \Auth()->user()->id, $ticket->id );

            $resposta = $ticket->id;

            if( \Auth()->user()->id == $request->solicitante['id'] )
            {
                $resposta = 'proprio/'.$ticket->id;
            }
          
            return ['status'=>true, 'mensagem' => $this->success->msgStore( $this->entidade ), 'id' => $resposta ];   
        }
        catch(\Exception $e)
        {
           return \Response::json(['errors' => [$this->errors->msgStore( $this->entidade ) ]], 404);
        }
    }

    private function validaCamposAdicionais( $request )
    {
        if( isset($request->campo_adicional) )
        {
            foreach ($request->campo_adicional as $campo)
            {
                $tk_campos = TicketCampos::find( $campo['id'] );

                if( $campo['id'] == '' || ( $tk_campos->obrigatorio == true && $campo['value'] == '' ) )
                {
                    throw new \Exception("Error: CampoAdicionaRequired", 1);
                }
            }
        }
        else
        {
            throw new \Exception("Error: CampoAdicionaRequired", 1);
        }
    }

    private function validaCamposAdicionaisCreate( $request )
    {
        if( isset($request->campo_adicional) )
        {
            foreach ($request->campo_adicional as $campo)
            {
                if( $campo['id'] == '' || ( $campo['value'] == '' ) )
                {
                    throw new \Exception("Error: CampoAdicionaRequired", 1);
                }
            }
        }
        else
        {
            throw new \Exception("Error: CampoAdicionaRequired", 1);
        }
    }

    public function adicionaTicketCampoTicket( $ticket_id, $camposAdicionais )
    {
        foreach ($camposAdicionais as $campo)
        {
            $campo_adicional = TicketCampoTicket::insertGetId(
                [ 
                    'ticket_id'       => $ticket_id,
                    'ticket_campo_id' => $campo['id'],
                    'resposta'        => $campo['value'],
                ] 
            );
        }
    }

    public function editaTicketCampoTicket( $ticket_id, $camposAdicionais )
    {
        foreach ($camposAdicionais as $campo)
        {
            $campo_adicional = TicketCampoTicket::where( 'ticket_id', '=', $ticket_id )->where( 'ticket_campo_id', '=', $campo['id'] )->first();
             

            if( is_null( $campo_adicional ) )
            {

                $old = '';
                
                $campo_adicional = new TicketCampoTicket();
                $campo_adicional->ticket_id = $ticket_id;
                $campo_adicional->ticket_campo_id = $campo['id'];
            }
            else
            {
                $old = $campo_adicional->resposta;
            }
                $new = $campo['value'];
            $campo_adicional->resposta = $campo['value'];
            $campo_adicional->save();

            if ($old != $new){
                $campo = TicketCampos::where( 'id', '=', $campo['id'] )
                                        ->select('nome')
                                        ->first();
               
                $mensagem = 'alterou '.$campo->nome.' de "'.$old.'" para "'.$new.'"';
                $movimentacao = '{"Alteração":"'.$campo->nome.'", "antigo":"'.$old.'", "novo":"'.$new.'" }';
                $historico = TicketHistorico::insertGetId(
                    [ 
                        'usuario_id' =>  \Auth()->user()->id, 
                        'ticket_id' => $ticket_id,
                        'mensagem' => $mensagem,
                        'movimentacao' => $movimentacao,
                        'icone' => 'pencil',
                        'cor' => '  #428bca',
                        'interno' => 'false',
                        'interacao' => 'false',
                    ] 
                );             
            }
        }
    }

    public function codigoTicket()
    {
        $tk_count = Ticket::whereRaw('TO_CHAR(tickets.created_at, \'YYYY\') = TO_CHAR(NOW(), \'YYYY\')')->count();
        $tk_count = str_pad(($tk_count + 1), 4, "0", STR_PAD_LEFT);
        
        return 'TK'.date_format(date_create(Carbon::now('America/Sao_Paulo')), 'y').$tk_count;
    }

    public function formataCamposAdicionaisHtml( $campos, $method = '' )
    {
        foreach ($campos as $campo) 
        {
            if( $campo->tipo == 'TEXTO' )
            {
                $campo->html = $this->retornaTexto( $campo, $method );
            }
            else if( $campo->tipo == 'TEXTO LONGO' )
            {
                $campo->html = $this->retornaTextoLongo( $campo, $method );
            }
            else if( $campo->tipo == 'LISTA' )
            {
                $campo->html = $this->retornaLista( $campo, $method );
            }
        }

        return $campos;
    }

    public function retornaTexto( $campo, $method = '' )
    {
        $padrao = '';

        if( $campo->padrao != '' )
        {
            $padrao = 'value="'.$campo->padrao.'"';
        }

        return '<input 
                    type="text" 
                    class="form-control input-sm" 
                    name="campo_adicional" 
                    data-campo-id="'.$campo->id.'" 
                    '.$padrao.' 
                    data-error="Este campo é obrigatório." 
                    '.$this->htmlRequired( $campo, $method ).' 
                    style="text-transform: uppercase;">';
    }

    public function retornaTextoLongo( $campo, $method = '' )
    {
        $padrao = '';

        if( $campo->padrao != '' )
        {
            $padrao = $campo->padrao;
        }

        return '<textarea 
                    rows="3"
                    class="form-control input-sm" 
                    name="campo_adicional" 
                    data-campo-id="'.$campo->id.'" 
                    data-error="Este campo é obrigatório." 
                    '.$this->htmlRequired( $campo, $method ).' 
                    style="text-transform: uppercase; resize:none;">'.$padrao.'</textarea>';
    }

    public function retornaLista( $campo, $method = '' )
    {   
        $option = '';
        $selected = false;

        $array_dados = json_decode($campo->dados, true);
        usort($array_dados, array($this, "cmp"));

        foreach ($array_dados as $lista) 
        {
            if( $campo->padrao == $this->comparadorPadrao( $lista, $method ) )
            {
                $selected = true;
                $option .= '<option value="'.$lista['valor'].'" selected>'.$lista['valor'].'</option>';
            }
            else
            {
                $option .= '<option value="'.$lista['valor'].'">'.$lista['valor'].'</option>';
            }
        }

        if( !$selected || ($campo->obrigatorio == false && $method == 'edit') )
        {
            $option = '<option value=""></option>'.$option;
        }

        return '<select 
                    class="form-control input-sm" 
                    name="campo_adicional" 
                    data-campo-id="'.$campo->id.'" 
                    data-error="'. $this->msgCampoObrigatorio( $campo ) .'"
                    '.$this->htmlRequired( $campo, $method ).'>'.$option.'</select>';
    }

    public function cmp($a, $b)
    {
        return $a["valor"] > $b["valor"];
    } 

    public function comparadorPadrao( $lista, $method = '' )
    {
        if( $method == 'edit' )
        {
            return $lista['valor'];
        }
        else
        {
            return $lista['id'];
        }
    }

    public function htmlRequired( $campo, $method = '' )
    {
        if( $method == 'create' )
        {
            $retorno = 'required';
        }else if( $campo->obrigatorio == false ){
            $retorno = '';
        }else{
            $retorno = 'required';
        }

        return $retorno;
    }

    public function msgCampoObrigatorio( $campo, $method = '' ){
        if( $campo->obrigatorio == false )
        {
            return '';
        }        

        return 'Este campo é obrigatório.';   
    }



    private function getAvatar($id){
        return Funcionario::where('id', $id)->select('avatar')->first()->avatar;
    }

    private function showTicket ( $id, $ability, $permissao, $method )
    {
        $ticketOjb = Ticket::where('id', '=', $id)->first();         
        
        /*
         * Valida as permissões de visualizar ticket
         */
        $this->autorizacaoTicket( $ability, $ticketOjb, $permissao );

        if( $ticketOjb == null )
        {
            return response()->view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ], 404 );
        } 

        $tickets_responder = $this->autorizacaoTicket( 'getPermissaoTicketResponderBool', $ticketOjb, $permissao );

        if( !$tickets_responder )
        {
            $historicos = $interacoes = TicketHistorico::with(['usuarios'])
            ->where('ticket_id', $id)
            ->where('interno', false)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        }else{

            $historicos = $interacoes = TicketHistorico::with(['usuarios'])
            ->where('ticket_id', $id)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        }

        if( !$tickets_responder ) {
            $campos_adicionais = TicketCampoTicket::where('ticket_id', $id)
            ->select('tickets_campo_ticket.resposta', 'tickets_campo.nome','tickets_campo.tipo')
            ->leftJoin('tickets_campo', 'tickets_campo_ticket.ticket_campo_id', 
                '=', 
                'tickets_campo.id')
            ->where('tickets_campo.visivel',true)
            ->get();
        } else {
            $campos_adicionais = TicketCampoTicket::where('ticket_id', $id)
            ->select('tickets_campo_ticket.resposta', 'tickets_campo.nome','tickets_campo.tipo')
            ->leftJoin('tickets_campo', 'tickets_campo_ticket.ticket_campo_id', 
                '=', 
                'tickets_campo.id')
            ->get();
        }

        $ticket = $this->repository->scopeQuery(function($query){
            return $query->orderBy('created_at', 'asc');
        })->findWhere([ ['id', '=', $id] ], ['*'])->first();
        
        $interacoes = $interacoes->filter(function ($value, $key) {
            $value->tempo_interacao = $this->dataInteracoes($value->created_at);
            $value->data_interacao = date_format(date_create( $value->created_at ), 'd/m/Y H:i:s');
            $value->avatar = $value->usuario_id;
            return $value['interacao'];
        });

       $historicos = $historicos->filter(function ($value, $key) {
            return !$value['interacao'];
        });
        
        if( is_null( $ticket->funcionario_responsavel_avatar ) ){
            $funcionario_responsavel_avatar = 'fantasma'  ;
        }else{
            $funcionario_responsavel_avatar = $ticket->usuario_responsavel_id;
        }


        if( is_null( $ticket->updated_at ) || $ticket->updated_at == $ticket->created_at ){                        
            $updated_at = null;
        }else{
            $updated_at = $this->dataHistorico( $ticket->updated_at );
        }

        //atualizacao
        if( is_null( $ticket->updated_at ) )
        {
            $dt_atualizacao = $title_atualizacao = null;
        }
        else
        {                        
            $title_atualizacao = date_format(date_create( $ticket->updated_at ), 'd/m/Y H:i:s');
            
            if( date('d/m/Y') == date_format(date_create( $ticket->updated_at ), 'd/m/Y') )
            {
                $dt_atualizacao = date_format(date_create( $ticket->updated_at ), 'H:i:s');    
            }
            else
            {
                $dt_atualizacao = date_format(date_create( $ticket->updated_at ), 'd/m/Y');
            }
        }

        //previsao
        if( is_null( $ticket->dt_previsao ) )
        {
            $dt_previsao = $title_previsao = '-';
        }
        else
        {
            $dt_previsao = date_format(date_create( $ticket->dt_previsao ), 'd/m/Y');
            $title_previsao = date_format(date_create( $ticket->dt_previsao ), 'd/m/Y');
        }

        // Ajusta data de notificação
        if( is_null( $ticket->dt_notificacao ) ){
            $dt_notificacao = $title_notificacao = '-';
            $title_notificacao = 'Sem notificação';
        }
        else
        {
            $title_notificacao = date_format(date_create( $ticket->dt_notificacao ), 'd/m/Y H:i');
            if( date('d/m/Y') == date_format(date_create( $ticket->dt_notificacao ), 'd/m/Y') )
                $dt_notificacao = date_format(date_create( $ticket->dt_notificacao ), 'H:i:s');
            else
                $dt_notificacao = date_format(date_create( $ticket->dt_notificacao ), 'd/m/Y');
        }

        //verifica se ticket esta fechado
        if( !$ticket->status_aberto ){
            $ticket_fechado = true;
            
            $title_resolucao = date_format(date_create( $ticket->dt_resolucao ), 'd/m/Y H:i:s');
            if ( empty($ticket->dt_resolucao) ){
                $resolucao = '-';
                $title_resolucao = '';
            }
            else if( date('d/m/Y') == date_format(date_create( $ticket->dt_resolucao ), 'd/m/Y') )
                $resolucao = date_format(date_create( $ticket->dt_resolucao ), 'H:i:s');
            else
                $resolucao = date_format(date_create( $ticket->dt_resolucao ), 'd/m/Y');

            $title_fechamento = date_format(date_create( $ticket->dt_fechamento ), 'd/m/Y H:i:s');
            if ( empty($ticket->dt_fechamento) ){
                $fechamento = '-';
                $title_fechamento = '';
            }
            else if( date('d/m/Y') == date_format(date_create( $ticket->dt_fechamento ), 'd/m/Y') )
                $fechamento = date_format(date_create( $ticket->dt_fechamento ), 'H:i:s');
            else
                $fechamento = date_format(date_create( $ticket->dt_fechamento ), 'd/m/Y');
            
        }else{
            $ticket_fechado = false;
            $fechamento = $resolucao = $title_fechamento = $title_resolucao = '';
        }

        //abertura                    
        if( date('d/m/Y') == date_format(date_create( $ticket->created_at ), 'd/m/Y') ){
            $abertura = date_format(date_create( $ticket->created_at ), 'H:i:s');    
        }else{
            $abertura = date_format(date_create( $ticket->created_at ), 'd/m/Y');
        }

        $tk_status_aberto = TicketStatus::where('ativo', '=', true)->where('id', '=', $ticket->status_id)->select('*')->first();

        $imagens = TicketImagem::where('ticket_id', $id)->orderBy('created_at', 'desc')->get();

        $proprio = false;
        if( $method == 'showProprio' )
        {
            $proprio = true;
        }

        $ticket_acao = TicketAcao::where('ativo', '=', true)
            ->where('departamento_id', '=', $ticketOjb->departamento_id)
            ->select('id', 'nome', 'status_atual', 'icone', 'solicitante_executa', 'responsavel_executa', 'trata_executa')->orderBy('ordem')->get();

        $ticket_acao = $ticket_acao->filter(function ($acao) use ($ticketOjb, $tickets_responder, $proprio)
        {
            $status_atual = json_decode($acao->status_atual);
            $executa = false;

            if( $ticketOjb->usuario_solicitante_id == \Auth()->user()->id )
            {
                $executa = $acao->solicitante_executa;
            }

            if( $ticketOjb->usuario_responsavel_id == \Auth()->user()->id && $executa == false && $proprio == false )
            {
                $executa = $acao->responsavel_executa;
            }

            if( $tickets_responder && $executa == false && $proprio == false )
            {
                $executa = $acao->trata_executa;
            }

            return in_array($ticketOjb->tickets_status_id, $status_atual) && $executa;
        });

        /**
         * Campos adicionais para as ações
         */
        $campos = new TicketCampoTicket(); 
        $campos = $this->formataCamposAdicionaisHtml( $campos->campoTicketResposta( $ticket->id, false ), 'edit' );
        
        $tk_campos = new TicketCampos(); 
        $tk_campos = $this->formataCamposAdicionaisHtml( $tk_campos->campoTicketDepartamentoEdit( $ticket->departamento_id, false ) );

        $campos_departamento = $tk_campos->merge( $campos );

        return view('ticket.visualizar', [
            'ticket' => $ticketOjb, 
            'ticket_id' => $id, 
            'codigo' => $ticket->codigo, 
            'departamento' => $ticket->departamento_nome, 
            'prioridade' => $ticket->prioridade_nome, 
            'prioridade_cor' => $ticket->prioridade_cor, 
            'status' => $tk_status_aberto->nome, 
            'status_aberto' => $tk_status_aberto->aberto,
            'status_cor' => $ticket->status_cor,
            
            'criado' => $this->dataHistorico( $ticket->created_at),
            'title_criado' => $this->dataHistorico($ticket->created_at),
            'solicitante' => $ticket->usuario_solicitante_nome,
            'funcionario_solicitante_avatar'=>$ticket->usuario_solicitante_id,
            
            'responsavel' => $ticket->usuario_responsavel_nome,
            'funcionario_responsavel_avatar'=>$funcionario_responsavel_avatar,
            'atualizado' => $updated_at,

            'assunto' =>  $ticket->assunto,
            'categoria_pai' => $ticket->categoria_nome_pai, 
            'categoria_filho' => $ticket->categoria_nome_filho, 

            //CAMPOS ADICIONAIS
            'campos_adicionais' =>  $this->ajustaLinkCampoAdicional($campos_adicionais),

            'descricao' => $this->ajustaLinkTexto($ticket->descricao),                            
            'abertura'       => $abertura,
            'title_abertura' => date_format(date_create( $ticket->created_at ), 'd/m/Y H:i:s'),                            
            'atualizacao'       => $dt_atualizacao,
            'title_atualizacao' => $title_atualizacao,                            
            'previsao'       => $dt_previsao,
            'title_previsao' => $title_previsao,                        

            //historico
            'historicos' => $historicos,
            'interacoes' => $this->ajustaLinkCollection($interacoes),

            //se o ticket estiver fechado
            'ticket_fechado' => $ticket_fechado,

            'resolucao' => $resolucao,
            'title_resolucao' => $title_resolucao,
            'fechamento' => $fechamento,
            'title_fechamento' => $title_fechamento,
            'notificacao' => $dt_notificacao,
            'title_notificacao' => $title_notificacao,
            'imagens' => $imagens,

            // //interacoes
            // //historico
            'proprio' => $proprio,
            'titulo' => ($proprio?'Meus tickets':'Tratar tickets'),
            'acoes' => $ticket_acao,
            'campos_departamento' => $campos_departamento,
            'migalhas' => $this->migalhaDePao( 'VISUALIZAR_TICKET' )

        ]);

    }  

    public function ajustaLinkTexto($content){
        $formataString = new FormatString();
        $content = $formataString->ajustaLink( $content );
        
        return $content;
    }

    public function ajustaLinkCollection($content){

        
        $content = $content->map(function ($item, $key) {
            $formataString = new FormatString();
            $item->__set('mensagem', $formataString->ajustaLink($item->mensagem));
            return $item;
        });

        return $content;

    }

    public function ajustaLinkCampoAdicional($content){
        
        $content = $content->map(function ($item, $key) {
            if($item->tipo == "TEXTO" || $item->tipo == "TEXTO LONGO"){
                $formataString = new FormatString();
                $item->__set('resposta', $formataString->ajustaLink($item->resposta));
            }
            return $item;
        });

        return $content;

    }

    public function show( $id )
    {
        return $this->showTicket( $id, 'getPermissaoTicketVisualizar', 'TICKETS_VISUALIZAR_DEPARTAMENTO', 'show' );
    }

    public function showProprio( $id )
    {
        return $this->showTicket( $id, 'getPermissaoTicketVisualizarProprio', 'TICKETS_VISUALIZAR_PROPRIO', 'showProprio' );
    }

    private function ticketAberto($id){
        
        try{

            $aberto = Ticket::where('tickets.id', '=', $id)  
            ->select('tickets_status.aberto')
            ->leftJoin('tickets_status', 'tickets.tickets_status_id', '=', 'tickets_status.id')
            ->first()->aberto;               
               
            if(!$aberto){
                abort(403);
            }

        }catch( \Exception $e ){
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {        
        $ticket = Ticket::where('id', '=', $id)->first();
        
        $this->ticketAberto( $id );
      

        /*
         * Permissão de visualizar esse ticket
         */
        $this->autorizacaoTicket( 'getPermissaoTicketVisualizar', $ticket );

        /*
         * Permissão de editar esse ticket
         */
        $this->autorizacaoTicket( 'getPermissaoTicketEditar', $ticket, 'TICKETS_TODOS_EDITAR' );

        if( $ticket ==  null )
        {
            return response()->view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ], 404 );
        }
                  
        $status = $this->getTicketStatusEditDoDepartamento( $ticket->tickets_status_id, $ticket->departamento_id );
       
        $solicitantes = $this->getTicketSolicitantesEdit( $ticket->usuario_solicitante_id );

        $prioridades = $this->getTicketPrioridadesEditDoDepartamento( $ticket->tickets_prioridade_id, $ticket->departamento_id );
        
        $subcategoria = $this->getTicketSubCategoriaEdit( $ticket->tickets_categoria_id );

        $categoria = $this->getTicketCategoriaEditDoDepartamento( $ticket->tickets_categoria_id, $ticket->departamento_id ); 

        $responsavel = $this->getTicketResponsavelEdit( $ticket->usuario_responsavel_id, $ticket->departamento_id, false );

        $campos = new TicketCampoTicket(); 
        
        $campos = $this->formataCamposAdicionaisHtml( $campos->campoTicketResposta( $ticket->id ), 'edit' );

        $tk_campos = new TicketCampos(); 

        $tk_campos = $this->formataCamposAdicionaisHtml( $tk_campos->campoTicketDepartamentoEdit( $ticket->departamento_id ) );                    

        $campos_departamento = $tk_campos->merge( $campos );

        if( $ticket->dt_previsao != '' )
        {
            $ticket->dt_previsao = date_format(date_create($ticket->dt_previsao), 'd/m/Y');
        }
                   
        return view('ticket.editar', [
            'ticket' => $ticket, 
            'status' => $status, 
            'solicitantes' => $solicitantes, 
            'prioridades' => $prioridades, 
            'categoria' => $categoria, 
            'subcategoria' => $subcategoria,
            'campos' => $campos_departamento,
            'responsavel' => $responsavel,
            'migalhas' => $this->migalhaDePao( ), 
        ]);
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
        else
        {
            $valor = date_format(date_create($created_at), 'd/m');
        }

        return $valor;        

    }

    /**
     * Este metodo faz a conta pra mostrar a dta referente ( ex:se for ate 59 minutos, se for mais de uma hora em horas )
     */
    private function dataInteracoes( $created_at ){

        $date1 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create( $created_at ), 'd/m/Y H:i:s'));
        $date2 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create(Carbon::now('America/Sao_Paulo')), 'd/m/Y H:i:s'));

        if( $date2->diffInDays($date1) == 1 ) {
            $valor = '1 dia atrás'; 
        } else if( $date2->diffInDays($date1) > 1 ) {
            $valor = $date2->diffInDays($date1).' dias atrás';
        } else if( $date2->diffInHours($date1) == 1 ) {
            $valor = '1 hora atrás';
        } else if( $date2->diffInHours($date1) > 1 ) {
            $valor = $date2->diffInHours($date1).' horas atrás';
        } else if( $date2->diffInMinutes($date1) == 1 ) {
            $valor = '1 minuto atrás';
        } else if( $date2->diffInMinutes($date1) > 1 ) {
            $valor = $date2->diffInMinutes($date1).' minutos atrás';
        } else {
            $valor = $date2->diffInSeconds($date1).' segundos atrás';
        }

        return $valor;        

    }
    
    /**
     * 
     * Retorna lista do status referente ao departamento do ticket
     * 
     * @param int $ticket_status_id
     * @param int $departamento_id
     * @return TicketStatus
     */
    private function getTicketStatusEditDoDepartamento( $tickets_status_id, $departamento_id )
    {        
        $tk_status = TicketStatus::where('ativo', '=', true)->where('departamento_id', '=', $departamento_id )->select('id', 'nome')->orderBy('ordem')->get();
        return $this->getSelected( $tk_status, $tickets_status_id );
    }

    /**
     * 
     * Retorna lista de prioridade referente ao departamento do ticket
     * 
     * @param int $ticket_prioridade_id
     * @param int $departamento_id
     * @return TicketPrioridade
     */
    private function getTicketPrioridadesEditDoDepartamento( $tickets_prioridade_id, $departamento_id )
    {
        $prioridades = TicketPrioridade::where('ativo', '=', true)->where('departamento_id', '=', $departamento_id)->select('id', 'nome')->orderBy('prioridade')->get();
        return $this->getSelected( $prioridades, $tickets_prioridade_id );
    }

    /**
     * 
     * Retorna lista de categoria referente ao departamento do ticket
     * 
     * @param int $ticket_categoria_id
     * @param int $departamento_id
     * @return TicketCategoria
     */
    private function getTicketCategoriaEditDoDepartamento( $tickets_categoria_id, $departamento_id )
    {
        $tk_subcategoria = TicketCategoria::where('id', '=', $tickets_categoria_id)->select('ticket_categoria_id')->first();
        
        $categoria = TicketCategoria::whereNull('ticket_categoria_id')->where('departamento_id', '=', $departamento_id)->select('id', 'nome', 'dicas')->orderBy('nome')->get();

        return $this->getSelected( $categoria, $tk_subcategoria->ticket_categoria_id );
    }
    
    public function getTicketStatusEdit( $tickets_status_id )
    {
        $tk_status = TicketStatus::where('ativo', '=', true)->select('id', 'nome')->orderBy('ordem')->get();
        return $this->getSelected( $tk_status, $tickets_status_id );
    }
    
    private function getTicketSolicitantesEdit( $usuario_solicitante_id )
    {
        $solicitantes = Usuario::where('ativo', '=', true)->select('id', 'nome')->orderBy('nome')->get();
        return $this->getSelected( $solicitantes, $usuario_solicitante_id );
    }

    public function getTicketPrioridadesEdit( $tickets_prioridade_id )
    {
        $prioridades = TicketPrioridade::where('ativo', '=', true)->select('id', 'nome')->orderBy('prioridade')->get();
        return $this->getSelected( $prioridades, $tickets_prioridade_id );
    }
    

    public function getTicketResponsavelEdit( $usuario_responsavel_id, $departamento_id, $exibe_vazio = false )
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

        $responsavel = $this->getSelected( $responsavel->unique()->sortBy('nome')->values(), $usuario_responsavel_id );

        if( $usuario_responsavel_id == '' || $exibe_vazio )
        {
            $usuario = new Usuario();
            $usuario->id = null;
            $usuario->nome = '';

            $responsavel->prepend($usuario);
        }

        return $responsavel;
    }

    public function getTicketSubCategoriaEdit( $tickets_categoria_id )
    {
        $tk_subcategoria = TicketCategoria::where('id', '=', $tickets_categoria_id)->select('id', 'nome', 'ticket_categoria_id')->first();
        $subcategoria = TicketCategoria::where('ticket_categoria_id', '=', $tk_subcategoria->ticket_categoria_id)->select('id', 'nome', 'dicas')->orderBy('nome')->get();

        return $this->getSelected( $subcategoria, $tickets_categoria_id );
    }    

    public function getSelected( $lista, $comparador )
    {
        foreach ($lista as $obj)
        {
            if( $comparador == $obj->id )
            {
                $obj->__set('selected', 'selected');
            }
            else
            {
                $obj->__set('selected', '');
            }
        }

        return $lista;
    }

    public function update(TicketRequest $request, $id)
    {
        $ticket_old = $ticket = Ticket::where('id', '=', $id)->first();

        /*
         * Permissão de visualizar esse ticket
         */
        $this->autorizacaoTicket( 'getPermissaoTicketVisualizar', $ticket );

        /*
         * Permissão de editar esse ticket
         */
        $this->autorizacaoTicket( 'getPermissaoTicketEditar', $ticket );
        
        try
        {
            $this->validaCamposAdicionais( $request );

            if ( empty($request->subcategoria['id']) )
                return \Response::json(['errors' => [ 'subcategoria' => ['O campo subcategoria é obrigatório'] ] ], 422);
                       
            $string = new Str;

            $request['nome'] = $string->upper($request->nome);
            $request['descricao'] = $string->upper($request->descricao);
            $request['funcionario_id'] = $request->gestor['id'];

            $this->repositoryTicket->update(
                [
                    'tickets_status_id'           => $request->status,
                    'usuario_solicitante_id'      => $request->solicitante,
                    'tickets_prioridade_id'       => $request->prioridade,
                    'assunto'                     => $string->upper($request->assunto),
                    'tickets_categoria_id'        => $request->subcategoria['id'],
                    'usuario_responsavel_id'      => $request->responsavel,
                    'dt_previsao'                 => $request->dt_previsao,                  
                ], $id);

            $this->editaTicketCampoTicket( $ticket->id, $request->campo_adicional );

            /*
             *  Salva Historico
             */
            $ticket_new =  $ticket = Ticket::where('id', '=', $id)->first();    
            $this->ticket_historico->historicoTicketUpadate( $ticket_old, $ticket_new, \Auth()->user()->id, $ticket->id );
                /**
                 * set_Interação 
                 * Interação da pagina de editar 
                 * @param int $id - id do ticket
                 * @param bool  $request->interna
                 * @param text  $request->mensagem
                 */
            $this->setInteracao($request);
            
            return ['status'=>true, 'mensagem' => $this->success->msgUpdate( $this->entidade ), 'id' => $id ];
        }
        catch( \Exception $e )
        {
            return \Response::json(['errors' => [$this->errors->msgUpdate( $this->entidade )] ], 404);
        }
    }

    public function downloadExcel(ExcelController $excel, $type ) {    

        $this->autorizacao( 'TICKETS_EXPORTAR' );

        $titulo_excel = array( 'Código', 'Data de criação','Hora de criação', 'Solicitante', 'Assunto', 'Categoria', 'Sub categoria', 'Responsável', 'Status', 'Prioridade', 'Departamento', 'Avaliação', 'Descrição', 'Data atualização', 'Hora atualização', 'Data resolução', 'Hora resolução','Data fechamento' , 'Hora fechamento' ,'Data previsão','Hora previsão',  'Data notificação',  'Hora notificação' );

        $filter_permissao = $this->autorizacaoTicket( 'getPermissaoTicketVisualizarIndex', Ticket::class );
       
        $filter_permissao = $this->autorizacaoTicket( 'getPermissaoTicketVisualizarIndex', Ticket::class );
       
        $string = new Str;
        $query = TicketView::query();    

        $query = $query->selectRaw( "
            codigo,
            created_at AS data,
            created_at AS hora,
            usuario_solicitante_nome, assunto, categoria_nome_pai,
            categoria_nome_filho, usuario_responsavel_nome, status_nome, 
            prioridade_nome, 
            departamento_nome, 
            avaliacao, 
            descricao, 
            updated_at AS update, 
            updated_at AS hora_update, 
            dt_resolucao AS resolução, 
            dt_resolucao AS hora_resolução, 
            dt_fechamento AS fechamaento, 
            dt_fechamento AS hora_fechamaento, 
            dt_previsao  AS previsao,  
            dt_previsao  AS hora_previsao, 
            dt_notificacao  AS notificacao, 
            dt_notificacao  AS hora_notificacao"
        );


        if (Input::has('codigo')) {                
            $query = $query->whereRaw( "sem_acento(\"codigo\") LIKE sem_acento('%".$string->upper(Input::get('codigo'))."%')" );
        }
        if (Input::has('assunto')) {                
            $query = $query->whereRaw( "sem_acento(\"assunto\") LIKE sem_acento('%".$string->upper(Input::get('assunto'))."%')" );
        }

        if( Input::get('aberto') == "true" ){
            $query = $query->where( "status_aberto", "=", true );
        } else if( Input::get('aberto') == "false" ) {
            $query = $query->where( "status_aberto", "=", false );
        }

        if ( Input::get('statuse') != 0 ) {            
            $query = $query->where( "status_id", "=", (int)Input::get('statuse'));
        }
        
        if (Input::get('categoria') != 0) {            
            $query = $query->where( "categoria_id_pai", "=", (int)Input::get('categoria'));
        }

        if ( Input::get('prioridade') != 0 )  {            
            $query = $query->where( "prioridade_id", "=", (int)Input::get('prioridade'));
        }

        if ( Input::get('departamento') != 0) {            
            $query = $query->where( "departamento_id", "=", (int)Input::get('departamento'));
        }

        if ( Input::get('usuario_solicitante') != 0) {            
            $query = $query->where( "usuario_solicitante_id", "=", (int)Input::get('usuario_solicitante'));
        }
        if ( Input::get('usuario_responsavel') != 0) {            
            $query = $query->where( "usuario_responsavel_id", "=", (int)Input::get('usuario_responsavel'));
        }
        if (!(is_null(Input::get('de')))) {
            $query = $query->whereRaw( "date(created_at) >= ". ( \DB::raw( "date('".Carbon::createFromFormat('d/m/Y', Input::get('de'))->format('Y-m-d') ) ) ."')" );
        }
        if (!(is_null(Input::get('ate')))) {
            $query = $query->whereRaw( "date(created_at) <= ". ( \DB::raw( "date('".Carbon::createFromFormat('d/m/Y', Input::get('ate'))->format('Y-m-d') ) ) ."')" );
        }
        if ( $filter_permissao == false) {            
            $query = $query->where( "departamento_id", "=", $this->getDepartamentoDeFuncionarioLogado() );
        }
        
        $query = $query->orderByRaw('created_at desc'); 
        $dados = $query->get();

        $CamposTeste = new TicketCampos();

        foreach ($dados as $dado) {   
           
            $dado->__set( 'hora', $this->date()->formataHoraExcel($dado['data']) );
            $dado->__set( 'data', $this->date()->formataDataExcel($dado['data']) );

            $dado->__set( 'update', $this->date()->formataDataExcel($dado['update']) );
            $dado->__set( 'hora_update', $this->date()->formataHoraExcel($dado['hora_update']) );

            $dado->__set( 'resolução', $this->date()->formataDataExcel($dado['resolução']) );
            $dado->__set( 'hora_resolução', $this->date()->formataHoraExcel($dado['hora_resolução']) );

            $dado->__set( 'fechamaento', $this->date()->formataDataExcel($dado['fechamaento']) );
            $dado->__set( 'hora_fechamaento', $this->date()->formataHoraExcel($dado['hora_fechamaento']) );

            $dado->__set( 'previsao', $this->date()->formataDataExcel($dado['previsao']) );
            $dado->__set( 'hora_previsao', $this->date()->formataHoraExcel($dado['hora_previsao']) );

            $dado->__set( 'notificacao', $this->date()->formataDataExcel($dado['notificacao']) );
            $dado->__set( 'hora_notificacao', $this->date()->formataHoraExcel($dado['hora_notificacao']) );
          
            $descricao = preg_replace( '/[\n]+/' , ' ', $dado['descricao']);
            $descricao = str_replace( ',' , ';', $descricao);
            $dado->__set( 'descricao', $descricao);
           
            $campos = $CamposTeste->camposAdicionaisTicket( $dado['codigo']);
            foreach ($campos as $campo) {
                $dado->__set( $campo['nome'], $campo['resposta']);
                    $titulo = ucfirst(strtolower($campo['nome']));
                if (!in_array(  $titulo  , $titulo_excel)) {
                    array_push($titulo_excel, $titulo );
                }
            }
        }
        $formato = [
                'B' => "dd/mm/yyyy", 
                'C' => "hh:mm:ss", 
                'N' => "dd/mm/yyyy", 
                'O' => "hh:mm:ss",
                'P' => "dd/mm/yyyy", 
                'Q' => "hh:mm:ss",
                'R' => "dd/mm/yyyy", 
                'S' => "hh:mm:ss",
                'T' => "dd/mm/yyyy", 
                'U' => "hh:mm:ss",
                'V' => "dd/mm/yyyy", 
                'W' => "hh:mm:ss",
                    ];
        return $excel->downloadExcel($type, $dados->toArray(), 'Tickets_', $titulo_excel,$formato);       

    }

    public function setInteracao( Request $request )
    {
        $ticketOjb = Ticket::where('id', '=', $request->id)->first();

        $departamento = Departamento::where('id', '=', $ticketOjb->departamento_id )->select('nome')->first();

        if( $request->interno )
        {
            $this->autorizacaoTicket( 'getPermissaoTicketResponder', $ticketOjb );
        }
        
        $ticketMensagem = TicketHistorico::insertGetId(
            [ 
                'usuario_id' => \Auth()->user()->id,
                'ticket_id' => $request->id,
                'mensagem'  => $request->mensagem,
                'icone'     => $this->getIconeTicketHistorico( $request ), 
                'interacao' => TRUE, 
                'interno'   => $request->interno,
                'cor'       => '#2ECC71',
            ] 
        );

        $ticketHistorico = TicketHistorico::insertGetId(
            [ 
                'usuario_id' => \Auth()->user()->id,
                'ticket_id' => $request->id,
                'mensagem'  => $this->getMensagemTicketHistorico( $request ),
                'icone'     => 'comment-o', 
                'interacao' => FALSE, 
                'interno'   => $request->interno,
                'cor'       => ($request->interno)?'#F39C12':'#5CB85C',
            ] 
        );

        $id_usuario = $this->getUsuarioTicketNotificacao( $request );

        if( isset($id_usuario) && $id_usuario != 0 )
        {
            foreach ($id_usuario as $value) {
                event(new NotificacaoEvent( $ticketMensagem, ['notificacao' => $this->getNotificacaoInteracao( $request, $value, $ticketOjb, $departamento )]));
            }
        }

        return ['status'=>true];
    }

    private function getIconeTicketHistorico( $request )
    {
        if( $request->interno )
        {
            return 'lock';
        }

        return 'comment-o';
    }

    private function getNotificacaoInteracao( $request, $id_usuario, $ticket, $departamento )
    {
        return [
            'titulo' => $this->getNomeDepartamento( $departamento->nome ),
            'mensagem' => $this->getMensagemTicketNotificacao( $ticket, $request ),
            'modulo' => $this->getModuloTicketNotificacao( $id_usuario, $ticket->usuario_solicitante_id ),
            'icone' => 'ticket',
            'cor' => '#dd4b39',
            'usuario' => [$id_usuario],
            'id' => $request->id,
        ];
    }

    private function getNomeDepartamento( $str )
    {
        return str_replace('de ti', 'de TI', $this->ucFirstCustom($str));
    }

    private function getMensagemTicketNotificacao( $ticket, $request )
    {
        if( $request->interno )
        {
            return '#'.$ticket->codigo.' - Nova nota interna';
        }

        return '#'.$ticket->codigo.' - Nova interação';
    }

    private function getModuloTicketNotificacao( $id_usuario, $usuario_solicitante_id )
    {
        if( $id_usuario == $usuario_solicitante_id )
        {
            return 'meus_tickets';
        }

        return 'ticket';
    }

    private function getMensagemTicketHistorico( $request )
    {
        if( $request->interno )
        {
            return 'publicou uma nota interna.';
        }

        return 'publicou uma interação.';
    }

    private function getUsuarioTicketNotificacao( $request )
    {
        $ticket = Ticket::where('id', '=', $request->id)->first();

        if( $request->interno && \Auth()->user()->id != $ticket->usuario_responsavel_id )
        {
            return [$ticket->usuario_responsavel_id];
        }
        else if( $request->interno == false && !is_null($ticket->usuario_responsavel_id) && \Auth()->user()->id == $ticket->usuario_solicitante_id )
        {
            return [$ticket->usuario_responsavel_id];
        }
        else if ($request->interno == false && \Auth()->user()->id != $ticket->usuario_solicitante_id )
        {
            if(\Auth()->user()->id != $ticket->usuario_responsavel_id && !is_null($ticket->usuario_responsavel_id)){
                return [$ticket->usuario_solicitante_id, $ticket->usuario_responsavel_id];     
            }
            return [$ticket->usuario_solicitante_id];
        }
        return 0;
    }

    public function getAcaoMacro($id, Request $required)
    {
        $ticket = Ticket::where('id', '=', $required->ticket_id)->first();

        $ticket_acao = TicketAcao::where('ativo', '=', true)
            ->where('id', '=', $id)
            ->select('interacao', 'nota_interna', 'campos', 'status_novo')->first();

        // Json com os campos que vão ser exibidos na tela
        $array_campos = json_decode($ticket_acao->campos);

        // Variávies que vão ser envidas na requisição ajax
        $responsavel = '';
        $ticket_status = '';
        $data_previsao = '';
        $solicitantes = '';
        $subcategoria = '';
        $categoria = '';
        $prioridade = '';
        $data_notificacao = '';
        $assunto = '';

        if( in_array('responsavel', $array_campos ) )
        {
            $responsavel = $this->getTicketResponsavelEdit( $ticket->usuario_responsavel_id, $ticket->departamento_id, false );
        }

        if( in_array('data_previsao', $array_campos ) )
        {
            if( $ticket->dt_previsao != '' )
            {
                $data_previsao = date_format(date_create($ticket->dt_previsao), 'd/m/Y');
            }
        }

        if( in_array('solicitante', $array_campos ) )
        {
            if( \Auth()->user()->id == $ticket->usuario_solicitante_id )
            {
                $solicitantes = $this->getSolicitantes( $ticket->departamento_id );
            }
            else
            {
                $solicitantes = $this->getTicketSolicitantesEdit( $ticket->usuario_solicitante_id );
            }
        }

        if( in_array('categoria', $array_campos ) )
        {
            $subcategoria = $this->getTicketSubCategoriaEdit( $ticket->tickets_categoria_id );

            $categoria = $this->getTicketCategoriaEditDoDepartamento( $ticket->tickets_categoria_id, $ticket->departamento_id );
        }

        if( in_array('prioridade', $array_campos ) )
        {
            $prioridade = $this->getTicketPrioridadesEditDoDepartamento( $ticket->tickets_prioridade_id, $ticket->departamento_id );
        }

        if( in_array('data_notificacao', $array_campos ) )
        {
            if( $ticket->dt_notificacao != '' )
            {
                $data_notificacao = date_format(date_create($ticket->dt_notificacao), 'd/m/Y H:i');
            }
        }

        if( in_array('assunto', $array_campos ) )
        {
            if( $ticket->assunto != '' )
            {
                $assunto = $ticket->assunto;
            }
        }

        // Status para onde o ticket pode ir de acordo com a ação selecionada
        $ticket_status = TicketStatus::where('ativo', '=', true)->whereIn('id', json_decode($ticket_acao->status_novo))->select('id', 'nome')->get();
        $ticket_status = $this->getSelected( $ticket_status, $ticket->tickets_status_id );
        if( !is_null($ticket_status) && $ticket_status->count() > 1 )
        {
            $tk_status = new TicketStatus();
            $tk_status->id = null;
            $tk_status->nome = '';
            $tk_status->selected = '';

            $ticket_status->prepend($tk_status);
        }


        return [ 
            'status'=>true, 
            'acao' => $ticket_acao, 
            'responsavel' => $responsavel,
            'ticket_status' => $ticket_status,
            'dt_previsao' => $data_previsao,
            'solicitantes' => $solicitantes,
            'subcategoria' => $subcategoria,
            'categoria' => $categoria,
            'prioridade' => $prioridade,
            'dt_notificacao' => $data_notificacao,
            'assunto' => $assunto,
        ];
    }

    /**
     * @author Felipe Brasil
     */
    public function executaMacro( Request $request )
    {
        /**
         * Salva Historico de Ação
         */        
        $ticketAcao = TicketAcao::where('id', $request->acoes_macro['acao_id'])->select('nome')->first();

        $ticket_historico_acao = TicketHistorico::create([
            'usuario_id' =>   \Auth()->user()->id,          
            'ticket_id'  => $request->acoes_macro['ticket_id'],            
            'mensagem'   => "executou a ação \"" . $ticketAcao->nome . "\"",
            'movimentacao' => '{}',
            'icone'      => 'reply',
            'cor'        => '#30BBBB',
            'interno' => 'false',
            'interacao'    => 'false'                
        ]);        

        $ticket_old = Ticket::where('id', '=', $request->acoes_macro['ticket_id'])->first();

        $array_sets = array();

        $ticket_acao = TicketAcao::where('ativo', '=', true)
            ->where('id', '=', $request->acoes_macro['acao_id'])
            ->select('campos', 'status_atual', 'status_novo')->first();

        // Valida se o status atual do ticket está em status atual da ação
        if( !in_array($ticket_old->tickets_status_id, json_decode($ticket_acao->status_atual) ) )
        {
            return \Response::json(['errors' => [$this->errors->msgUpdate( $this->entidade ), 'O status do ticket não possui essa ação.'] ], 404);
        }
        
        // Json com os campos que vão ser exibidos na tela
        $array_campos = json_decode($ticket_acao->campos);
        
        if( isset($request->acoes_macro['responsavel']) && in_array('responsavel', $array_campos ) )
        {
            if( is_null($request->acoes_macro['responsavel']['id']) || $request->acoes_macro['responsavel']['id'] == '' )
            {
                return \Response::json(['errors' => [$this->errors->msgUpdate( $this->entidade ), 'Campo responsável não preenchido.'] ], 404);
            }

            $array_sets['usuario_responsavel_id'] = $request->acoes_macro['responsavel']['id'];
        }

        if( isset($request->acoes_macro['dt_previsao']) && in_array('data_previsao', $array_campos ) )
        {
            $array_sets['dt_previsao'] = $request->acoes_macro['dt_previsao'];
        }
        else if( in_array('data_previsao', $array_campos ) )
        {
            $array_sets['dt_previsao'] = null;
        }

        if( isset($request->acoes_macro['solicitante']) && in_array('solicitante', $array_campos ) )
        {
            $array_sets['usuario_solicitante_id'] = $request->acoes_macro['solicitante']['id'];
        }

        if( isset($request->acoes_macro['subcategoria']) && in_array('categoria', $array_campos ) )
        {
            $array_sets['tickets_categoria_id'] = $request->acoes_macro['subcategoria']['id'];
        }

        if( isset($request->acoes_macro['status']) && in_array($request->acoes_macro['status'], json_decode($ticket_acao->status_novo) ) )
        {
            $array_sets['tickets_status_id'] = $request->acoes_macro['status'];
        }

        if( isset($request->acoes_macro['prioridade']) && in_array('prioridade', $array_campos ) )
        {
            $array_sets['tickets_prioridade_id'] = $request->acoes_macro['prioridade']['id'];
        }

        if( isset($request->acoes_macro['assunto']) && in_array('assunto', $array_campos ) )
        {
            $array_sets['assunto'] = $this->strToUpperCustom($request->acoes_macro['assunto']);
        }

        /**
         * Campos adicionais
         */
        if( isset($request->acoes_macro['campo_adicional']) && in_array('campos_adicionais', $array_campos) )
        {
            $this->validaCamposAdicionais( (object) $request->acoes_macro );
            $this->editaTicketCampoTicket( $request->acoes_macro['ticket_id'], $request->acoes_macro['campo_adicional'] );
        }

        if( isset($request->acoes_macro['dt_notificacao']) && in_array('data_notificacao', $array_campos ) )
        {
            $array_sets['dt_notificacao'] = $request->acoes_macro['dt_notificacao'];
        }
        else if( in_array('data_notificacao', $array_campos ) )
        {
            $array_sets['dt_notificacao'] = null;
        }

        if( isset($request->acoes_macro['avaliacao']) && in_array('avaliacao', $array_campos ) )
        {
            $array_sets['avaliacao'] = $request->acoes_macro['avaliacao'];
        }
        
        $this->repositoryTicket->update( $array_sets, $request->acoes_macro['ticket_id'] );


        /*
         *  Salva Historico
         */
        $ticket_new = Ticket::where('id', '=', $request->acoes_macro['ticket_id'])->first();
        $this->ticket_historico->historicoTicketUpadate( $ticket_old, $ticket_new, \Auth()->user()->id, $request->acoes_macro['ticket_id'] );

        /*
         *  Envia notificação
         */
        if ( isset($request->acoes_macro['interacao']) && isset($request->acoes_macro['texto_interacao']) ){
            $request['id'] = $request->acoes_macro['ticket_id'];
            $request['mensagem'] = $this->strToUpperCustom( $request->acoes_macro['texto_interacao'] );
            $request['interno'] = $request->acoes_macro['interacao'];
            $this->setInteracao( $request );
        }


        return ['status'=>true, 'mensagem' => $this->success->msgUpdate( $this->entidade ) ];
    }

}
