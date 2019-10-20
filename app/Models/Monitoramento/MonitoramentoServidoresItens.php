<?php

namespace App\Models\Monitoramento;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Util\Date;
use App\Util\FormatString;
use App\Models\Configuracao\Sistema\Parametro;

class MonitoramentoServidoresItens extends Model
{
    protected $table = 'monitoramento_servidores_itens';

    protected $fillable = array(
		'monitoramento_servidores_id',
		'identificador',
		'monitoramento_servidores_status_id',
		'nome',
		'mensagem',
		'valores',
        'dt_status',
		'updated_at',
		'contador_falhas',
        'chamado_vinculado',
        'chamado_vinculado_titulo',
        'chamado_vinculado_at',
        'usuario_inclusao_chamado_id'
	);

    public function getDtStatusAttribute( $value ) {

        if( !is_null($value)){
            $data = new Date;
            $date1 = Carbon::now();
            $segundos = $date1->diffInSeconds( Carbon::parse( $value) );

            return  $data->dataIntervaloSegundoString( $segundos );
        }
        return $value;
    }
	/**
     * [deleteMonitorServidoresItens Deleta o registro do servidor do Banco de dados do FENIX ]
     * @param  [type] $id [ID da tabela MONITORAMENTO SERVIDOR ITENS]
     */
	public function deleteMonitorServidoresItens( $id ) {
		MonitoramentoServidoresItens::where( 'monitoramento_servidores_id' , $id)->delete();
	}

    /**
     * [getServidoresItenStatus - Retorna os IDs dos servidores cujo iten esta no status recebido]
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    public function getServidoresItenStatus( $status = [], $item = [] , $mensagem = '', $paradaProgramada = [], $chamado_vinculado = [], $chamado = ''  ){

        return MonitoramentoServidoresItens::select('monitoramento_servidores_id')
            ->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', '=', 'monitoramento_servidores_itens.monitoramento_servidores_status_id')
            ->queryWhereInStatus($status)
            ->queryWhereInItens($item)
            ->queryWhereMensagem($mensagem)
            ->queryWhereChamado($chamado)
            ->queryWhereFiltroPersistente($paradaProgramada)
            ->queryWhereChamadoVinculado($chamado_vinculado)
            ->distinct()
            ->get();        
    }

    public function scopeQueryWhereChamadoVinculado($query, $value){
        
        if( !empty( $value ) && count($value) < 2 )
        {
            if ( in_array('SIM', $value) ){
                $query->whereNotNull('monitoramento_servidores_itens.chamado_vinculado');
            }else{
                $query->whereNull('monitoramento_servidores_itens.chamado_vinculado');
            }
        }
    }

    public function scopeQueryWhereInStatus( $query, $value ) {
        if( !empty( $value ) ) {   
            $query->whereIn('monitoramento_servidores_itens.monitoramento_servidores_status_id', collect($value)->pluck('id'));
        }
    }

    public function scopeQueryWhereInItens( $query, $value ) {
        if( !empty( $value ) ) {
            $query->whereIn('monitoramento_servidores_itens.identificador', collect($value)->pluck('identificador'));
        }
    }
    
    public function scopeQueryWhereMensagem( $query, $value ) {
        if( $value != '' ) {
            $string = new FormatString;
            $query->whereRaw('upper(monitoramento_servidores_itens.mensagem) LIKE \'%'.$string->strToUpperSemAcento($value).'%\'');
        }
    }

    public function scopeQueryWhereChamado( $query, $value ) {
        if( $value != '' ) {
            $string = new FormatString;
            $query->whereRaw('upper(monitoramento_servidores_itens.chamado_vinculado) LIKE \'%'.$string->strToUpperSemAcento($value).'%\'');
        }
    }  

    public function scopeQueryWhereFiltroPersistente( $query, $value ){
         
         if( !empty( $value ) && count($value) < 2 )
            {

                $alarmaPersistente = $this->getParametroAlarmePersistente();
                    $query->where('monitoramento_servidores_status.alarme', true);

                if ( in_array( 'SIM', $value ) ){
                    $query->whereRaw('monitoramento_servidores_itens.contador_falhas > '.$alarmaPersistente);
                }else{
                    $query->whereRaw('monitoramento_servidores_itens.contador_falhas < '.$alarmaPersistente );
                }
            }     
    }

    public function getParametroAlarmePersistente() {
        return  Parametro::select('valor_numero')->where('nome', '=', 'MONITORAMENTO_QUANTIDADE_COLETA_FALHA_PERSISTENTE')->first()->valor_numero;
    }

    public function abaItensMonitorados( $id ){

        return MonitoramentoServidoresItens::select(
            'monitoramento_servidores_itens.nome', 
            'monitoramento_servidores_status.nome AS status', 
            'monitoramento_servidores_status.cor', 
            'monitoramento_servidores_status.icone',
            'monitoramento_servidores_itens.dt_status', 
            'monitoramento_servidores_itens.mensagem'            
        )
        ->selectRaw('( TO_CHAR(monitoramento_servidores.dt_ultima_coleta , \'dd/mm/yyyy hh24:mi:ss\') ) as ultima_coleta')
        ->where('monitoramento_servidores_itens.monitoramento_servidores_id', $id)
        ->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_itens.monitoramento_servidores_status_id', '=', 'monitoramento_servidores_status.id')
        ->leftJoin('monitoramento_servidores', 'monitoramento_servidores.id', '=', 'monitoramento_servidores_itens.monitoramento_servidores_id')
        ->orderBy('monitoramento_servidores_itens.nome', 'asc')->get();
    }

    // public function (){

    // }

    public function getItensServidores( $id = null )
    {
        return $this->queryItensServidores($id)->distinct('identificador')->orderBy('nome')->get();
    }

    public function scopeQueryItensServidores( $query, $value ) {
        if( !empty( $value ) )
        {
            $query->select('id', 'nome');
            $query->where('monitoramento_servidores_id', $value);
        }
        else
        {
            $query->select('identificador', 'nome');
        }
    }
}
