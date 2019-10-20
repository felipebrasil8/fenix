<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketCampos extends Model
{
    protected $table = 'tickets_campo';
    protected $fillable = array(
		//	
	);

    // public function campoTicketDepartamentoCreate( $id_departamento = '' )
    // {
    //     return TicketCampos::where('ativo', '=', true)
    //         ->where('visivel', '=', true)
    //         ->where('obrigatorio', '=', true)
    //         ->select('id', 'nome', 'descricao', 'padrao', 'tipo', 'dados' )
    //         ->orderBy('id')
    //         ->get();
    // }

    public function campoTicketDepartamentoEdit( $id_departamento, $somente_visivel = true )
    {
        if ( $somente_visivel )
            return TicketCampos::where('ativo', '=', true)
                ->where('visivel', '=', true)
                ->where( 'departamento_id', '=', $id_departamento )
                ->select('id', 'nome', 'descricao', 'padrao', 'tipo', 'dados', 'obrigatorio')
                ->orderBy('id')
                ->get();
        else
            return TicketCampos::where('ativo', '=', true)
                ->where( 'departamento_id', '=', $id_departamento )
                ->select('id', 'nome', 'descricao', 'padrao', 'tipo', 'dados', 'obrigatorio')
                ->orderBy('id')
                ->get();

    }

    public function camposAdicionaisTicket($codigo)
    {

        return TicketCampos::selectRaw( "tickets_view.id, tickets_campo.nome, tickets_campo_ticket.resposta"  )
                    ->leftJoin('tickets_view', 'tickets_view.departamento_id', '=', 'tickets_campo.departamento_id')
                    ->leftJoin('tickets_campo_ticket', function ($join) {
                            $join->on('tickets_campo_ticket.ticket_campo_id', '=', 'tickets_campo.id')
                            ->on( 'tickets_campo_ticket.ticket_id', '=', 'tickets_view.id' );
                        })
        
                    ->where('tickets_view.codigo', '=', $codigo)
                    ->orderBy('tickets_campo.id')
                    ->get();
    }

    public function tickets(){
        return $this->belongsToMany('App\Models\Ticket\Ticket', 'tickets_campo_ticket', 'ticket_campo_id', 'ticket_id');
    }
    
    /**
     * Irá localizar os campos adicionais conforme seu departamento
     */
    public function getCamposAdicionais( $id_departamento )
    {
        return TicketCampos::where('ativo', '=', true)
            ->where('visivel', '=', true)
            ->where('obrigatorio', '=', true)
            ->where('departamento_id', '=', $id_departamento)
            ->select('id', 'nome', 'descricao', 'padrao', 'tipo', 'dados', 'visivel', 'obrigatorio' )
            ->orderBy('id')
            ->get();
    }

    public function getNomeAttribute($value) {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }

}
