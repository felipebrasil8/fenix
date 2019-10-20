<?php

namespace App\Models\Configuracao\Ticket;

use Illuminate\Database\Eloquent\Model;


class Campo extends Model
{
    
    protected $table = 'tickets_campo';

    protected $fillable = array(
		'nome',
		'descricao',
		'visivel',
        'obrigatorio',
        'departamento_id',
        'padrao',
        'tipo',
        'dados'
	);
    
}