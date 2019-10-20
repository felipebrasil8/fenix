<?php

namespace App\Models\Monitoramento;

use Illuminate\Database\Eloquent\Model;

class ChamadosAbertosView extends Model
{
    protected $connection = 'crm';
    protected $table = 'fenix.chamados_abertos_view';

    public function getChamadosProjeto( $projeto )
    {

 		return ChamadosAbertosView::where('projeto', '=', $projeto )->orderby('dt_abertura', 'desc' )->get();   	
    }

    public function getInfoChamadoProjeto($chamado){
 		
 		return ChamadosAbertosView::where('numero', '=', $chamado )->first();   	

    }
}
