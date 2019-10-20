<?php

namespace App\Models\Monitoramento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Monitoramento\MonitoramentoServidores;

class MonitoramentoServidoresStatus extends MonitoramentoServidores
{
	use SoftDeletes;
    protected $table = 'monitoramento_servidores_status';

    public function getStatusIten ()
    {
    	return MonitoramentoServidoresStatus::
                select(
                    'monitoramento_servidores_status.id', 
                    'monitoramento_servidores_status.nome', 
                    'monitoramento_servidores_status.alarme',
                    'monitoramento_servidores_status.icone',
                    'monitoramento_servidores_status.cor'
                )
                ->selectRaw(' 0 as total')
                ->where('monitoramento_servidores_status.filtro_item', true)
                ->orderBy('peso', 'desc')
                ->get(); 
	}

    public function getStatusServidor ()
    {
		return MonitoramentoServidoresStatus::
            select(
                    'monitoramento_servidores_status.id', 
                    'monitoramento_servidores_status.nome', 
                    'monitoramento_servidores_status.alarme',
                    'monitoramento_servidores_status.icone',
                    'monitoramento_servidores_status.cor'
                )     
            ->selectRaw(' 0 as total')
            ->where('monitoramento_servidores_status.filtro_servidor', true)
            ->orderBy('peso', 'desc')
            ->get(); 
	}


}
