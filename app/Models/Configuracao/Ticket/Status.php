<?php

namespace App\Models\Configuracao\Ticket;

use Illuminate\Database\Eloquent\Model;


class Status extends Model
{
    
    protected $table = 'tickets_status';

    protected $fillable = array(
    	'id',
        'departamento_id',
		'nome',
		'descricao',		
        'ordem',
        'aberto',
        'cor'
	);
    
}
