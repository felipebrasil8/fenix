<?php

namespace App\Models\Monitoramento;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Monitoramento\MonitoramentoServidores;
use App\Models\Monitoramento\MonitoramentoServidoresItens;
use App\Models\Monitoramento\MonitoramentoServidoresColetas;
use App\Models\Monitoramento\MonitoramentoServidoresChamados;
use App\Models\Monitoramento\MonitoramentoServidoresItensHistoricos;
use App\Models\Monitoramento\MonitoramentoServidoresParadaProgramada;

class ServidoresClientesView extends Model
{
    protected $connection = 'crm';
    protected $table = 'fenix.servidores_clientes_view';


    public function getDataAttribute( $value ) 
    {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i:s');        
        }
        return $value;
    }


    public function getServidoresCrm( $filtro )
    {
        return $this->getStatusServidoresMonitoramento($filtro); 

    }   

    /**
     * [atualizaFenix ATUALIZA TABELA MONITORAMENTO SERVIDOR NO FENIX A PARTIR DA VIEW DO CRM]
     */
    public function atualizaFenix()
    {
        
        DB::beginTransaction();
        
        try{

            $monitoramentoServidores = new MonitoramentoServidores; 
            $monitoramentoServidoresItens = new MonitoramentoServidoresItens; 
            $monitoramentoServidoresColetas = new MonitoramentoServidoresColetas;
            $monitoramentoServidoresChamados = new MonitoramentoServidoresChamados;
            $monitoramentoServidoresItensHistoricos = new MonitoramentoServidoresItensHistoricos;
            $monitoramentoServidoresParadaProgramada = new MonitoramentoServidoresParadaProgramada;

            $servidoresCrm = ServidoresClientesView::whereNotNull('ip')->get();
            $servidoresFenix = $monitoramentoServidores->selectRaw('id, tipo, id_projeto as projeto')->get();
            
            $servidoresCrm->map(function ($item, $key) use ($monitoramentoServidores, $servidoresFenix) 
            {          
                // CASO NÃO EXISTA NO BANCO DE DADOS DO FENIX - INSERIR 
                               
                if ( !($servidoresFenix->flatten(1)->contains('projeto', $item->projeto) && $servidoresFenix->flatten(1)->contains('tipo', $item->tipo)) )
                {
                    $monitoramentoServidores->updateProjetoMonitorServidores($item);
                }
                
                $monitoramentoServidores->createOrUpdateMonitorServidores($item);

            });
            
            $servidoresFenix = $monitoramentoServidores->selectRaw('id, tipo, id_projeto as projeto')->get();

            $servidoresFenix->map(function ($item, $key) use ($servidoresCrm, $monitoramentoServidores,$monitoramentoServidoresItens,$monitoramentoServidoresColetas, $monitoramentoServidoresItensHistoricos, $monitoramentoServidoresChamados, $monitoramentoServidoresParadaProgramada ) 
            {       
                // CASO EXISTA NO BANCO DE DADOS DO FENIX E NÃO EXISTA NO CRM - DELETE 
                
                if ( !($servidoresCrm->flatten(1)->contains('tipo',$item->tipo) && $servidoresCrm->flatten(1)->contains('projeto', $item->projeto)) ){                   
                    
                    $monitoramentoServidoresChamados->deleteHistoricoChamadosServidor( $item->id );
                    $monitoramentoServidoresParadaProgramada->deleteMonitoramentoServidoresParadaProgramada( $item->id );
                    $monitoramentoServidoresItensHistoricos->deleteMonitorServidores( $item->id );
                    $monitoramentoServidoresColetas->deleteMonitorServidoresColetas( $item->id );
                    $monitoramentoServidoresItens->deleteMonitorServidoresItens( $item->id );
                    $monitoramentoServidores->deleteMonitorServidores( $item->id );
                    
                }
            });

            DB::commit();

            return 0;
       
        }
        catch(\Exception $e)
        {
            DB::rollback();

            return  0;
        }

    
    }


    public function getStatusServidoresMonitoramento($filtro)
    {
        $servidores = ServidoresClientesView::whereNotNull('ip')
                                           ->paginate($filtro->por_pagina);

        $servidores->data = $this->addStatus($servidores);                         
        return $servidores;
    }

    private function addStatus($collection)
    {
      
        $monitoramentoServidores = new MonitoramentoServidores;
                
        $collection = $collection->map(function ($item, $key) use ($monitoramentoServidores)
        {                    
            $servidoresFenix = $monitoramentoServidores::selectRaw('monitoramento_servidores_status.nome, monitoramento_servidores.porta_api')
            ->where('monitoramento_servidores.id_projeto', '=', $item->projeto)
            ->where('monitoramento_servidores.tipo', '=', $item->tipo)
            ->leftJoin ('monitoramento_servidores_status', 'monitoramento_servidores.monitoramento_servidores_status_id', '=', 'monitoramento_servidores_status.id')
            ->first();

            $item->statusServidor = $servidoresFenix->nome;
            $item->porta_api = $servidoresFenix->porta_api;


            return $item;
        });

        return $collection;
    }

}
