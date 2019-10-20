<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use App\Models\RH\Departamento;

class TicketPrioridade extends Model
{
    protected $table = 'tickets_prioridade';
    protected $fillable = array(
		//
	);

    public function departamentos()
    {
        return $this->hasMany(Departamentos::class);
    }
}
