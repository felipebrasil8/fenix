<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketCampoTicket extends Model
{
    protected $table = 'tickets_campo_ticket';

    /**
     * Variável para querys não adicionarem column created_at e updated_at
     */
    public $timestamps = false;
    
    protected $fillable = array(
		'ticket_id',
        'ticket_campo_id',
        'resposta',
	);

    public function campoTicketResposta( $ticket_id, $somente_visiveis = true )
    {
        if ( $somente_visiveis )
            return TicketCampoTicket::where('ticket_id', '=', $ticket_id)
                ->where('tickets_campo.visivel', '=', true)
                ->join('tickets_campo', 'tickets_campo_ticket.ticket_campo_id', '=', 'tickets_campo.id')
                ->select('tickets_campo.id', 'tickets_campo.nome', 'tickets_campo.descricao', 'tickets_campo_ticket.resposta AS padrao', 'tickets_campo.tipo', 'tickets_campo.dados', 'tickets_campo.obrigatorio' )
                ->orderBy('tickets_campo.created_at')
                ->get();
        else
            return TicketCampoTicket::where('ticket_id', '=', $ticket_id)
                ->join('tickets_campo', 'tickets_campo_ticket.ticket_campo_id', '=', 'tickets_campo.id')
                ->select('tickets_campo.id', 'tickets_campo.nome', 'tickets_campo.descricao', 'tickets_campo_ticket.resposta AS padrao', 'tickets_campo.tipo', 'tickets_campo.dados', 'tickets_campo.obrigatorio' )
                ->orderBy('tickets_campo.created_at')
                ->get();
    }

    public function getNomeAttribute($value) {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }
}