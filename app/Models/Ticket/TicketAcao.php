<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class TicketAcao extends Model
{
    protected $table = 'tickets_acao';
    protected $fillable = array(
        //  
    );

    protected $hidden = array(
        'solicitante_executa',
        'responsavel_executa',
        'trata_executa',
    );
}
