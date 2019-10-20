<?php

namespace App\Models\Configuracao\Ticket;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'tickets_categoria';
    
     protected $fillable = array(
		'nome',
		'descricao',
		'dicas',
		'ticket_categoria_id',
		'departamento_id'
	);

	
}
