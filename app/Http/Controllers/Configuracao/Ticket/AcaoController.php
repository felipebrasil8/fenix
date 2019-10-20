<?php

namespace App\Http\Controllers\Configuracao\Ticket;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\RH\Departamento;
use App\Models\Core\Icone;
use App\Models\Configuracao\Ticket\Acao;
use App\Models\Ticket\TicketAcao;
use App\Models\Ticket\TicketStatus;

class AcaoController extends Controller
{
    private $departamentos;
    private $page;

    public function __construct(){

       $this->departamentos = Departamento::where('ativo', '=', true)
                                ->where('ticket', '=', true)
                                ->select('id', 'nome')
                                ->orderBy('nome')->get();

        $this->page = "acao";
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->autorizacao('CONFIGURACAO_TICKET_ACOES_VISUALIZAR');
        
        return view( 'configuracao.ticket.acao.index', [ 
            'departamentos' => $this->departamentos,
            'departamento' => 'false',
            'edit' => false,
            'page' => $this->page,
            'migalhas' => $this->migalhaDePao( 'CONFIGURAR' ) ,
            'acoes' => 'false'
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
        $this->autorizacao('CONFIGURACAO_TICKET_ACOES_CADASTRAR'); 

        $this->validaStatusAtualVazio( $request );

        $interacao = $this->validaMensagemInteracao( $request );

        $nota_interna = $this->validaMensagemNotaInterna( $request );

        $campos = $this->formataCampos( $request );

        $status_novo = $this->formataStatusNovo( $request );

        $status_atual = $this->formataStatusAtual( $request );
        
        $ordem = TicketAcao::max('ordem');

        $ticketAcao = TicketAcao::insertGetId(
            [
                'usuario_inclusao_id' => \Auth()->user()->id, 
                'nome' => $this->ucFirstCustom($request->nome), 
                'icone' => $request->modal_icone, 
                'ordem' => ($ordem+1), 
                'descricao' => $this->strToUpperCustom($request->descricao), 
                'solicitante_executa' => $request->solicitante_executa,
                'responsavel_executa' => $request->responsavel_executa,
                'trata_executa' => $request->trata_executa,
                'interacao' => $interacao,
                'nota_interna' => $nota_interna,
                'campos' => json_encode($campos),
                'status_atual' => json_encode($status_atual),
                'status_novo' => json_encode($status_novo),
                'departamento_id' => $request->departamento_id,
            ] 
        );

        return redirect('configuracao/ticket/acao/departamento/'.$request->departamento_id);
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
        $this->autorizacao('CONFIGURACAO_TICKET_ACOES_VISUALIZAR'); 

        $acoes = Acao::where('tickets_acao.ativo', '=', true)
                        ->where('tickets_acao.departamento_id', '=', $id)
                        ->select(
                            'tickets_acao.id', 
                            'tickets_acao.icone', 
                            'tickets_acao.nome', 
                            'tickets_acao.descricao', 
                            'tickets_acao.solicitante_executa', 
                            'tickets_acao.responsavel_executa', 
                            'tickets_acao.trata_executa', 
                            'tickets_acao.interacao', 
                            'tickets_acao.nota_interna',
                            'tickets_acao.campos',
                            'tickets_acao.status_atual',
                            'tickets_acao.status_novo',
                            'icones.nome as icone_nome'
                        )
                        ->leftJoin('icones', 'tickets_acao.icone', '=', 'icones.icone')
                        ->orderBy('tickets_acao.ordem')->get();

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();

        $status = TicketStatus::where('ativo', '=', true)->where('departamento_id', '=', $id )->select('id', 'nome')->orderBy('ordem')->get();

        return view( 'configuracao.ticket.acao.index', [ 
            'departamentos' => $this->departamentos,
            'departamento' => $id,
            'acoes' => $acoes,
            'edit' => true,
            'icones' => $icones,
            'status' => $status,
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
        $this->autorizacao('CONFIGURACAO_TICKET_ACOES_EDITAR'); 

        $this->validaStatusAtualVazio( $request );

        $interacao = $this->validaMensagemInteracao( $request );

        $nota_interna = $this->validaMensagemNotaInterna( $request );

        $campos = $this->formataCampos( $request );

        $status_novo = $this->formataStatusNovo( $request );

        $status_atual = $this->formataStatusAtual( $request );

        Acao::where('id', $request->acao_id)
            ->update([
                'usuario_alteracao_id' => \Auth()->user()->id, 
                'nome' => $this->ucFirstCustom($request->nome), 
                'icone' => $request->modal_icone,  
                'descricao' => $this->strToUpperCustom($request->descricao), 
                'solicitante_executa' => $request->solicitante_executa,
                'responsavel_executa' => $request->responsavel_executa,
                'trata_executa' => $request->trata_executa,
                'interacao' => $interacao,
                'nota_interna' => $nota_interna,
                'campos' => json_encode($campos),
                'status_atual' => json_encode($status_atual),
                'status_novo' => json_encode($status_novo)
            ]);

        return redirect('configuracao/ticket/acao/departamento/'.$request->departamento_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->autorizacao('CONFIGURACAO_TICKET_ACOES_EXCLUIR'); 
        try
        {
            Acao::where('id', $id)->delete();
            return ['status' => true];
        }
        catch(\Exception $e)
        {
            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);
        }
    }

    private function validaStatusAtualVazio( $request )
    {
        if( !isset($request->status_atual) )
        {
            return redirect('configuracao/ticket/acao/departamento/'.$request->departamento_id);
        }
    }

    private function validaMensagemInteracao( $request )
    {
        if( isset($request->mensagem) && in_array('interacao', $request->mensagem) )
        {
            return true;
        }

        return false;        
    }

    private function validaMensagemNotaInterna( $request )
    {
        if( isset($request->mensagem) && in_array('nota_interna', $request->mensagem) )
        {
            return true;
        }

        return false;        
    }

    private function formataCampos( $request )
    {
        if( isset($request->campos) )
        {
            return $request->campos;
        }

        return array();
    }

    private function formataStatusNovo( $request )
    {
        $status_novo = array();
        if( isset($request->status_novo) )
        {
            for ($i=0; $i < count($request->status_novo); $i++)
            {
                array_push($status_novo, intval($request->status_novo[$i]));
            }
        }

        return $status_novo;
    }

    private function formataStatusAtual( $request )
    {
        $status_atual = array();
        for ($i=0; $i < count($request->status_atual); $i++)
        {
            array_push($status_atual, intval($request->status_atual[$i]));
        }

        return $status_atual;
    }

    /**
     * Metodo que atualiza Ação a cada movimento de drag and drop do usuario em Acao
     * @param Object Request
     * @return JSON
     */
    public function storeMultiplo(Request $request){

        try{

            $soma = 1;
            for ($i=0; $i < count($request->all()); $i++) {             

                $acao = Acao::find($request[$i]['id']);

                $acao->nome = $request[$i]['nome'];
                $acao->icone = $request[$i]['icone'];
                $acao->ordem = $soma*10;
                $acao->save();

                $soma++;
            }

        }catch(\Exception $e){
            
            return response()->json(['error' => 'Não foi possível atualizar ações'], 401);           
        }

        return response()->json(['success' => 'Ações atualizadas com sucesso'], 200);
    }
}
