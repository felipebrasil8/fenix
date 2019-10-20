<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class TicketView extends Model
{
    protected $table = 'tickets_view';

    protected $fillable = array(
		'codigo',
		'departamento_id',
		'status_id',
		'assunto',
		'categoria_id_filho',
		'prioridade_id',
		'usuario_solicitante_id',
		'usuario_responsavel_id'
	);

	public function TicketsAbertosDepartamento( $permissao, $departamento )
	{
        if( count( $permissao ) > 0 )
        {
	        return TicketView::select('id','funcionario_solicitante_nome', 'funcionario_responsavel_avatar','codigo','assunto','created_at','prioridade_nome','prioridade_cor','status_aberto', 'departamento_nome','status_nome','status_cor')
							->where('departamento_id', $departamento)
							->where('status_aberto', true )             
							->orderBy('status_ordem', 'asc')
							->orderBy('prioridade_ordem', 'desc')
							->orderBy('created_at', 'asc')
	                        ->get();
        }
        
		return TicketView::select('id','funcionario_solicitante_nome', 'funcionario_responsavel_avatar','codigo','assunto','created_at','prioridade_nome','prioridade_cor','status_nome','status_cor','status_aberto', 'departamento_nome')
						->where('status_aberto', true ) 
						->orderBy('status_ordem', 'asc')
						->orderBy('prioridade_ordem', 'desc')
						->orderBy('created_at', 'asc')
                        ->get();
    }

}
