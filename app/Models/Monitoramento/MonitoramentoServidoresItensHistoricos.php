<?php

namespace App\Models\Monitoramento;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use App\Util\Date;

class MonitoramentoServidoresItensHistoricos extends Model
{
	protected $table = 'monitoramento_servidores_itens_historicos';

    public $timestamps = false;

    protected $fillable = array(
        'data_entrada',
        'data_saida',
        'servidores_id',
        'servidores_itens_id',
        'servidores_status_id',
    );

    public function getDataEntradaAttribute( $value ) 
    {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i:s');        
        }
        return $value;
    }

    public function getDataSaidaAttribute( $value ) 
    {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i:s');        
        }
        else{
            return '-';
        }
    }

    public function deleteMonitorServidores($id)
    {
        MonitoramentoServidoresItensHistoricos::where('servidores_id', '=', $id)->delete();
    }

    public function tableMonitoramentoServidoresItensHistoricos( $id, $filtro )
    {
        $dados = MonitoramentoServidoresItensHistoricos::
                where('monitoramento_servidores_itens_historicos.servidores_id', '=', $id)
                ->selectRaw('
                    monitoramento_servidores_itens_historicos.id, 
                    monitoramento_servidores_itens_historicos.data_entrada, 
                    monitoramento_servidores_itens_historicos.data_saida, 
                    CASE WHEN monitoramento_servidores_itens_historicos.servidores_itens_id IS NULL THEN
                        \'SERVIDOR\'::text
                    ELSE 
                        monitoramento_servidores_itens.nome END alerta,
                    monitoramento_servidores_status.nome AS status,
                    monitoramento_servidores_status.cor AS status_cor,
                    monitoramento_servidores_status.icone AS status_icone,
                    CASE WHEN monitoramento_servidores_itens_historicos.data_saida IS NULL THEN
                        EXTRACT(epoch FROM NOW()) - EXTRACT(epoch FROM monitoramento_servidores_itens_historicos.data_entrada)
                    ELSE 
                        EXTRACT(epoch FROM monitoramento_servidores_itens_historicos.data_saida) - EXTRACT(epoch FROM monitoramento_servidores_itens_historicos.data_entrada)
                    END AS duracao,
                    monitoramento_servidores_itens_historicos.mensagem 
                ')
                ->leftJoin('monitoramento_servidores', 'monitoramento_servidores.id', '=', 'monitoramento_servidores_itens_historicos.servidores_id')
                ->leftJoin('monitoramento_servidores_itens', 'monitoramento_servidores_itens.id', '=', 'monitoramento_servidores_itens_historicos.servidores_itens_id')
                ->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', 'monitoramento_servidores_itens_historicos.servidores_status_id')
                ->queryWhereItens($filtro->item)
                ->queryWhereStatusServidores($filtro->status)
                ->queryWhereData($filtro->data_de, $filtro->data_ate)
                ->queryOrderBy($filtro->sort)
                ->paginate($filtro->por_pagina);    
    
        $dados->itens = $this->setDuracaoString($dados);

        return $dados;
    }

    public function setDuracaoString( $collection )
    {
        $collection = $collection->map(function($item){
            $data = new date;
            $item->duracao = $data->dataIntervaloSegundoString( $item->duracao );

        });

        return $collection; 
    }

    public function scopeQueryWhereStatusServidores( $query, $value )
    {
        if( !empty( $value ) ){
             return $query->whereIn('monitoramento_servidores_itens_historicos.servidores_status_id', collect($value)->pluck('id'));
        }                                    
    }

    public function scopeQueryWhereData( $query, $de, $ate )
    {
        if( !empty( $de ) || !empty( $ate ) ){
            //return $query->whereIn('monitoramento_servidores_itens_historicos.servidores_status_id', collect($value)->pluck('id'));
            if(!empty( $de )){
                $query->whereRaw("monitoramento_servidores_itens_historicos.data_entrada::DATE >= '".$de."'::DATE");
            }

            if(!empty( $ate )){
                $query->whereRaw("monitoramento_servidores_itens_historicos.data_saida::DATE <= '".$ate."'::DATE");
            }

            return $query;
        }                                    
    }

    public function scopeQueryWhereItens( $query, $value )
    {
        if( !empty( $value ) ){
            if(collect($value)->pluck('id')->contains(null)){
                return $query->whereNull('monitoramento_servidores_itens_historicos.servidores_itens_id')
                    ->orWhereIn('monitoramento_servidores_itens_historicos.servidores_itens_id', collect($value)->pluck('id'));
            }

            return $query->WhereIn('monitoramento_servidores_itens_historicos.servidores_itens_id', collect($value)->pluck('id'));
        }                                    
    }

    public function scopeQueryOrderBy( $query, $sort )
    {
        if ($sort != ''){
            return $query->orderByRaw($sort);
        }
    }
}
