<?php

namespace App\Models\Configuracao\Ticket;

use Illuminate\Database\Eloquent\Model;


class Prioridade extends Model
{
    
    protected $table = 'tickets_prioridade';

    protected $fillable = array(
    	'id',
		'nome',
		'cor',		
        'departamento_id',
        'prioridade_id',
        'prioridade',
        'descricao'
	);

    /**
     * Seta para uppercase descricao
     *
     * @param  string  $value
     * @return void
     */
    public function setDescricaoAttribute($value)
    {
        $this->attributes['descricao'] = strtoupper($value);
    }
    
}