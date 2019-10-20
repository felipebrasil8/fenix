<?php

namespace App\Models\Monitoramento;

use Illuminate\Database\Eloquent\Model;
use App\Models\Monitoramento\MonitoramentoServidoresItens;
use Carbon\Carbon;

class MonitoramentoServidoresChamados extends Model
{
    protected $table = 'monitoramento_servidores_chamados';

    protected $fillable = array(
        'created_at',
		'updated_at',
		'usuario_inclusao_id',
		'usuario_alteracao_id',
		'monitoramento_servidores_id', 
		'monitoramento_servidores_itens_id',
		'numero_chamado'
	);

    public function getCreatedAtAttribute( $value ) 
    {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i:s');        
        }
        return $value;
    }

    public function getUpdatedAtAttribute( $value ) 
    {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i:s');        
        }
        else{
            return '-';
        }
    }

   	public function setHistoricoInsertChamado($chamado_vinculado, $id_servidor, $id_item ){

   		
  		if ( $id_item == false ) 
  		{
			$ids = MonitoramentoServidoresItens::select('id')
										->where('monitoramento_servidores_id', $id_servidor)
										->get()
										->pluck('id');

			foreach ($ids as $id) {
				MonitoramentoServidoresChamados::insert([
		   			'usuario_inclusao_id' => \Auth::user()->id,
					'monitoramento_servidores_id' => $id_servidor,
					'monitoramento_servidores_itens_id' => $id,
					'numero_chamado' => $chamado_vinculado,
				]);
			}

  		}
  		else
  		{
	   		MonitoramentoServidoresChamados::insert([
	   			'usuario_inclusao_id' => \Auth::user()->id,
				'monitoramento_servidores_id' => $id_servidor,
				'monitoramento_servidores_itens_id' => $id_item,
				'numero_chamado' => $chamado_vinculado,
			]);

  		}




   	}

   	public function setHistoricoDeleteChamado ( $chamado_vinculado, $id_servidor, $id_item ){


   		if ( $id_item == false ) 
  		{


			MonitoramentoServidoresChamados::
			   		where('numero_chamado', $chamado_vinculado)
			   		->where('monitoramento_servidores_id', $id_servidor)
			   	 	->whereNull('usuario_alteracao_id')
			   		->whereNull('updated_at')
			   		->update([
						'usuario_alteracao_id' => \Auth::user()->id,
						'updated_at' => now()
					]);


  		}
  		else
  		{
	   		MonitoramentoServidoresChamados::
			   		where('numero_chamado', $chamado_vinculado)
			   		->where('monitoramento_servidores_id', $id_servidor)
			   		->where('monitoramento_servidores_itens_id', $id_item)
			   		->whereNull('usuario_alteracao_id')
			   		->whereNull('updated_at')
			   		->update([
						'usuario_alteracao_id' => \Auth::user()->id,
						'updated_at' => now()
		 			]);

  		}

   	

   	} 


   	public function abaVinculoChamados($id, $filtro){

   		return MonitoramentoServidoresChamados::
                where('monitoramento_servidores_chamados.monitoramento_servidores_id', '=', $id)
                ->selectRaw('
                	
                	monitoramento_servidores_chamados.id,
                	monitoramento_servidores_chamados.created_at,
                	monitoramento_servidores_chamados.updated_at,
                    usuarios.nome as usuario_inclusao,
                	ua.nome as usuario_alteracao,
                    monitoramento_servidores_itens.nome,
               	    monitoramento_servidores_chamados.numero_chamado

                ')
                
                ->leftJoin('usuarios', 'usuarios.id', 'monitoramento_servidores_chamados.usuario_inclusao_id')
                ->leftJoin('usuarios as ua', 'ua.id', 'monitoramento_servidores_chamados.usuario_alteracao_id')
                ->leftJoin('monitoramento_servidores', 'monitoramento_servidores.id', '=', 'monitoramento_servidores_chamados.monitoramento_servidores_id')
                ->leftJoin('monitoramento_servidores_itens', 'monitoramento_servidores_itens.id', '=', 'monitoramento_servidores_chamados.monitoramento_servidores_itens_id')
                
                ->queryWhereItens($filtro->item)
                ->queryWhereAlteracao($filtro->alteracao)
                ->queryWhereInclusao($filtro->inclusao)
                ->queryWhereData($filtro->data_de, $filtro->data_ate)
                ->queryOrderBy($filtro->sort)
                ->paginate($filtro->por_pagina);    

   	}

    public function scopeQueryWhereAlteracao( $query, $value )
    {
        if( !empty( $value ) ){
            return $query->WhereIn('monitoramento_servidores_chamados.usuario_alteracao_id', collect($value)->pluck('id'));
        }                                    
    }

    public function scopeQueryWhereInclusao( $query, $value )
    {
        if( !empty( $value ) ){
            return $query->WhereIn('monitoramento_servidores_chamados.usuario_inclusao_id', collect($value)->pluck('id'));
        }                                    
    }

    public function scopeQueryWhereItens( $query, $value )
    {
        if( !empty( $value ) ){
            if(collect($value)->pluck('id')->contains(null)){
                return $query->whereNull('monitoramento_servidores_chamados.monitoramento_servidores_itens_id')
                    ->orWhereIn('monitoramento_servidores_chamados.monitoramento_servidores_itens_id', collect($value)->pluck('id'));
            }

            return $query->WhereIn('monitoramento_servidores_chamados.monitoramento_servidores_itens_id', collect($value)->pluck('id'));
        }                                    
    }


    public function scopeQueryOrderBy( $query, $sort )
    {
        if ($sort != ''){
            return $query->orderByRaw($sort);
        }
    }

    public function scopeQueryWhereData( $query, $de, $ate )
    {
        if( !empty( $de ) || !empty( $ate ) ){
            //return $query->whereIn('monitoramento_servidores_itens_historicos.servidores_status_id', collect($value)->pluck('id'));
            if(!empty( $de )){
                $query->whereRaw("monitoramento_servidores_chamados.created_at::DATE >= '".$de."'::DATE");
            }

            if(!empty( $ate )){
                $query->whereRaw("monitoramento_servidores_chamados.updated_at::DATE <= '".$ate."'::DATE");
            }

            return $query;
        }                                    
    }


    /**
     * [deleteHistoricoChamadosServidor Responsável por escluir todo o historico de chamados atrelados aos itens de um servidor ]
     * @param  [int] $id_servidor [Id do servidor que será excluido o historico]
     */
    public function deleteHistoricoChamadosServidor( $id_servidor ) {

        MonitoramentoServidoresChamados::
            where('monitoramento_servidores_id', $id_servidor)
            ->delete();


    }

}
