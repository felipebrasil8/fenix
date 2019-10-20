<?php

namespace App\Models\Monitoramento;
use Carbon\Carbon;
use App\Util\Date;
use App\Util\FormatString;

use Illuminate\Database\Eloquent\Model;
/**
 * Model criada no sprint-031
 */
class MonitoramentoServidoresParadaProgramada extends Model
{
    protected $table = 'monitoramento_servidores_parada_programada';

    protected $fillable = array(
		'dt_inicio',
		'dt_fim',
		'usuario_inclusao_id',
		'usuario_alteracao_id',
		'monitoramento_servidores_id',
		'observacao',
		'ativo'
    );
    
    public function getDtInicioAttribute( $value ) {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i');        
        }
        return $value;
    }

    public function getDtFimAttribute( $value ) {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i');
        }
        return $value;
    }

    public function getServidoresParadasProgramadas( $value ){

        return MonitoramentoServidoresParadaProgramada::
                        select('monitoramento_servidores_id')
                        ->where('monitoramento_servidores_parada_programada.ativo', true)
                        ->queryWhereParadaProgramada($value)
                        ->groupBy('monitoramento_servidores_id')
                        ->get();
    }


    public function scopeQueryWhereParadaProgramada( $query, $value )
    {
        $query->where(function ($query) use ( $value ) {
            
            if( in_array('AGENDADA', $value) )
            {
                $query->orWhereRaw('monitoramento_servidores_parada_programada.dt_inicio > NOW()');
            }
            
            if( in_array('NO MOMENTO', $value) )
            {
                $query->orWhereRaw('(monitoramento_servidores_parada_programada.dt_inicio <= NOW() AND 
                                    monitoramento_servidores_parada_programada.dt_fim >= NOW() )');
            }            
        });
    
    }

    /**
     * deleteMonitoramentoServidoresParadaProgramada Deleta o registro do servidor do Banco de dados do FENIX
     * @param  [type] $id ID da tabela MONITORAMENTO SERVIDOR COLETAS
     */
    public function deleteMonitoramentoServidoresParadaProgramada( $id ){
        MonitoramentoServidoresParadaProgramada::where( 'monitoramento_servidores_id' , $id)->delete();
    }

    public function tableMonitoramentoServidoresParadaProgramada( $id, $filtro )
    {
        $dados = MonitoramentoServidoresParadaProgramada::
                where('monitoramento_servidores_parada_programada.monitoramento_servidores_id', '=', $id)
                ->selectRaw('
                    monitoramento_servidores_parada_programada.id, 
                    monitoramento_servidores_parada_programada.dt_inicio, 
                    monitoramento_servidores_parada_programada.dt_fim, 
                    EXTRACT(epoch FROM monitoramento_servidores_parada_programada.dt_fim) - EXTRACT(epoch FROM monitoramento_servidores_parada_programada.dt_inicio) AS duracao,
                    usuario_inclusao.nome AS usuario_inclusao_nome,
                    usuario_alteracao.nome AS usuario_alteracao_nome, 
                    monitoramento_servidores_parada_programada.observacao 
                ')
                ->leftjoin('usuarios AS usuario_inclusao', 'monitoramento_servidores_parada_programada.usuario_inclusao_id', '=', 'usuario_inclusao.id')
                ->leftjoin('usuarios AS usuario_alteracao', 'monitoramento_servidores_parada_programada.usuario_alteracao_id', '=', 'usuario_alteracao.id')
                ->leftJoin('monitoramento_servidores', 'monitoramento_servidores.id', '=', 'monitoramento_servidores_parada_programada.monitoramento_servidores_id')
                ->queryWhereData($filtro->data_de, $filtro->data_ate)
                ->queryWhereUsuario($filtro->usuario)
                ->queryWhereObservacao($filtro->observacao)
                ->queryOrderBy($filtro->sort)
                ->paginate($filtro->por_pagina);    
    
        $dados->itens = $this->setDuracaoString($dados);

        return $dados;
    }

    public function scopeQueryWhereUsuario( $query, $value )
    {
        if( !empty( $value ) ){
             return $query->whereIn('monitoramento_servidores_parada_programada.usuario_inclusao_id', collect($value)->pluck('id'));
        }                                    
    }

    public function scopeQueryWhereObservacao( $query, $value )
    {
        if( !empty( $value ) )
        {
            $string = new FormatString;
            return $query->where('monitoramento_servidores_parada_programada.observacao', 'LIKE', '%'.$string->strToUpperCustom($value).'%');
        }                                    
    }

    public function setDuracaoString( $collection )
    {
        $collection = $collection->map(function($item){
            $data = new date;
            $item->duracao = $data->dataIntervaloSegundoString( $item->duracao );

        });

        return $collection; 
    }

    public function scopeQueryWhereData( $query, $de, $ate )
    {
        if( !empty( $de ) || !empty( $ate ) ){
            //return $query->whereIn('monitoramento_servidores_parada_programada.servidores_status_id', collect($value)->pluck('id'));
            if(!empty( $de )){
                $query->whereRaw("monitoramento_servidores_parada_programada.dt_inicio::DATE >= '".$de."'::DATE");
            }

            if(!empty( $ate )){
                $query->whereRaw("monitoramento_servidores_parada_programada.dt_fim::DATE <= '".$ate."'::DATE");
            }

            return $query;
        }                                    
    }

    public function scopeQueryOrderBy( $query, $sort )
    {
        if ($sort != ''){
            return $query->orderByRaw($sort);
        }
    }
}
