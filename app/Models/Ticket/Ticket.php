<?php

namespace App\Models\Ticket;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Models\Configuracao\Usuario;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = array(
        'codigo',
        'created_at',
        'updated_at',
        'usuario_inclusao_id',
        'usuario_alteracao_id',
        'usuario_responsavel_id',
        'usuario_solicitante_id',
        'departamento_id',
        'tickets_categoria_id',
        'tickets_status_id',
        'tickets_prioridade_id',
        'avaliacao',
        'assunto',
        'cargo_id',
        'descricao',
        'dt_resolucao',
        'dt_fechamento',
        'dt_previsao',
        'dt_notificacao'
    );

    public function ticketscampos(){

        return $this->belongsToMany('App\Models\Ticket\TicketCampos', 'tickets_campo_ticket', 'ticket_id', 'ticket_campo_id');

    }

    public function tickethistorico(){
        return $this->hasMany('App\Models\Ticket\TicketHistorico');
    }

    public function departamento(){
        return $this->belongsTo('App\Models\RH\Departamento');
    }   

    public function MeusTicketsAbertos( $user_id ){
        return Ticket::where('tickets.usuario_solicitante_id', $user_id)
                        ->where('tickets_status.aberto', true)
                        ->leftJoin('tickets_status', 'tickets.tickets_status_id', '=', 'tickets_status.id')
                        ->select('id')
                        ->count();
    }
    
    public function getQtdeTicketsAbertos( $departamento_id ){
        return Ticket::where('tickets_status.aberto', true)
            ->where( 'tickets.departamento_id', $departamento_id )
            ->leftJoin('tickets_status', 'tickets.tickets_status_id', '=', 'tickets_status.id')
            ->select('*')
            ->count();
    }

    public function getQtdeTicketsAgrupadosStatusMesAtual( $departamento_id ){

        return Ticket::select('tickets_status.nome', DB::raw('count(*) as total'))
        ->where( 'tickets.departamento_id', $departamento_id )
            ->leftJoin('tickets_status', 'tickets.tickets_status_id', '=', 'tickets_status.id')
            ->groupBy('tickets_status.nome')
            ->get();
    }

    public function getQtdeTicketsAgrupadosResponsavelMesAtual( $departamento_id ){

        return Ticket::select('usuarios.nome', DB::raw('count(*) as total'))
            ->where( 'tickets.departamento_id', $departamento_id )
            ->leftJoin('usuarios', 'tickets.usuario_responsavel_id', '=', 'usuarios.id')
            ->groupBy('usuarios.nome')
            ->get();
                        
    }

    /**     
     * @param int $departamento_id
     * @return int
     */
    public function getQtdeTicketsNovosSemResponsavel( $departamento_id ){

        return Ticket::where( 'tickets_status.aberto', '=', true )        
            ->where( 'tickets.departamento_id', $departamento_id )
            ->whereNull('tickets.usuario_responsavel_id')
            ->leftjoin('tickets_status', 'tickets_status.id', '=', 'tickets.tickets_status_id')
            ->leftjoin('usuarios', 'usuarios.id', '=', 'tickets.usuario_responsavel_id')
            ->select('tickets.id')        
            ->count();
    }

    public static function getBuscaMenuTickets( $busca, $todos, $usuario_departamento, $filter_permissao )
    {
        $meusTickets = Ticket::select('tickets.id', 'usuarios.nome as responsavel', 'us.nome as solicitante')
            ->selectRaw('CASE WHEN tickets.usuario_responsavel_id IS NULL THEN \'fantasma\'
                ELSE tickets.usuario_responsavel_id::text                     
                END img')
            ->selectRaw('\'#\'||tickets.codigo||\' - \'||departamentos.nome AS d1')
            ->selectRaw('tickets.assunto AS d2')
            ->selectRaw('\'/ticket/proprio/\'||tickets.id AS url')
            ->whereRaw('tickets.codigo LIKE \''.$busca.'%\'')
            ->where('tickets.usuario_solicitante_id', '=', \Auth::user()->id )
            ->whereAtivoBuscaMenu($busca)
            ->leftJoin('departamentos', 'tickets.departamento_id', '=', 'departamentos.id')
            ->leftJoin('usuarios', 'tickets.usuario_responsavel_id', '=', 'usuarios.id')
            ->leftJoin('usuarios AS us', 'tickets.usuario_solicitante_id', '=', 'us.id')
            ->orderBy('tickets.id')
            ->get();

        $ticketsResponsaveis = Ticket::select('tickets.id', 'usuarios.nome as responsavel', 'us.nome as solicitante')
            ->selectRaw('CASE WHEN tickets.usuario_responsavel_id IS NULL THEN \'fantasma\'
                ELSE tickets.usuario_responsavel_id::text                     
                END img')
            ->selectRaw('\'#\'||tickets.codigo||\' - \'||departamentos.nome AS d1')
            ->selectRaw('tickets.assunto AS d2')
            ->selectRaw('\'/ticket/\'||tickets.id AS url')
            ->whereRaw('tickets.codigo LIKE \''.$busca.'%\'')
            ->where('tickets.usuario_responsavel_id', \Auth::user()->id)
            ->whereNotIn('tickets.id', $meusTickets->pluck('id') ) 
            ->whereAtivoBuscaMenu($busca)
            ->leftJoin('departamentos', 'tickets.departamento_id', '=', 'departamentos.id')
            ->leftJoin('usuarios', 'tickets.usuario_responsavel_id', '=', 'usuarios.id')
            ->leftJoin('usuarios AS us', 'tickets.usuario_solicitante_id', '=', 'us.id')
            ->orderBy('tickets.id')
            ->get();

        $tickets = $meusTickets->merge($ticketsResponsaveis);

        $ticketsPermissao = [];

        if( $todos || $filter_permissao )
        {
            $ticketsPermissao = Ticket::select('tickets.id', 'usuarios.nome as responsavel', 'us.nome as solicitante')
                ->selectRaw('CASE WHEN tickets.usuario_responsavel_id IS NULL THEN \'fantasma\'
                    ELSE tickets.usuario_responsavel_id::text                     
                    END img')
                ->selectRaw('\'#\'||tickets.codigo||\' - \'||departamentos.nome AS d1')
                ->selectRaw('tickets.assunto AS d2')
                ->selectRaw('\'/ticket/\'||tickets.id AS url')
                ->whereRaw('tickets.codigo LIKE \''.$busca.'%\'')
                ->where( function ( $query ) use ($todos, $filter_permissao, $usuario_departamento){
                    if( !$todos && $filter_permissao )
                    {
                        $query->where('tickets.departamento_id', '=', $usuario_departamento);
                    }  
                })
                ->whereNotIn('tickets.id', $meusTickets->pluck('id') ) 
                ->whereNotIn('tickets.id', $ticketsResponsaveis->pluck('id') )
                ->whereAtivoBuscaMenu($busca)
                ->leftJoin('departamentos', 'tickets.departamento_id', '=', 'departamentos.id')
                ->leftJoin('usuarios', 'tickets.usuario_responsavel_id', '=', 'usuarios.id')
                ->leftJoin('usuarios AS us', 'tickets.usuario_solicitante_id', '=', 'us.id')
                ->orderBy('tickets.id')
                ->get();
        }

        return $tickets->merge($ticketsPermissao);
    }

    public function scopeWhereAtivoBuscaMenu( $query, $busca )
    {
        if( strlen($busca) < 8 )
        {
            return $query->whereNull('dt_fechamento');
        }
    }
}
