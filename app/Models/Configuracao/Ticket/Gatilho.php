<?php

namespace App\Models\Configuracao\Ticket;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gatilho extends Model
{

    use SoftDeletes;


    protected $table = 'tickets_gatilho';
    
    protected $dates = ['deleted_at'];

    protected $softDelete = true;


    protected $fillable = array(
        'nome',
        'departamento_id',
        'quanto_executar',
        'acao',
      
    );

  
   

}

