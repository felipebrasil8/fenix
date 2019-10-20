<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Util\Date;

class HistoricoPesquisaPublicacaoView extends Model
{    
	protected $table = "historico_pesquisa_publicacao_view";

	public function getDadosHistoricoPesquisaPublicacaoView( $de, $ate ){
        
        $d = new Date;
        
        $de = $d->setFormatDate( $de, 'd/m/Y', 'Y-m-d', 'America/Sao_Paulo');
        $ate = $d->setFormatDate( $ate, 'd/m/Y', 'Y-m-d', 'America/Sao_Paulo');        

        return HistoricoPesquisaPublicacaoView::whereDate('data', '>=', $de)->whereDate('data', '<=', $ate)->orderByDesc('created_at')->get();
    }

    // data
    public function getDataAttribute($value){
        $data = new Date();
        return $data->formataDataExcel($value);
        
    } 

    // horario
    public function getHorarioAttribute( $value ){
        $data = new Date();
        return $data->formataHoraExcel($value);
        
    } 

    // nome
    public function getNomeAttribute( $value ){

    	if(!is_null($value)){
    		return $value;
    	}

    	return '-';
    } 

    // departamento
    public function getDepartamentoAttribute( $value ){

    	if(!is_null($value)){
    		return $value;
    	}

    	return '-';
    } 

    // area
    public function getAreaAttribute( $value ){

    	if(!is_null($value)){
    		return $value;
    	}

    	return '-';
    } 

	// busca
    public function getBuscaAttribute( $value ){

    	if(!is_null($value)){
    		return $value;
    	}

    	return '-';
    } 

    // ip
    public function getIpAttribute( $value ){

    	if(!is_null($value)){
    		return $value;
    	}

    	return '-';
    } 

	// qtde_resultados
    public function getQtdeResultadosAttribute( $value ){

    	if(!is_null($value)){
    		return ''.$value.'';
    	}

    	return '-';
    } 

    //pagina
    public function getPaginaAttribute( $value ){

    	if(!is_null($value)){
    		return $value;
    	}

    	return '-';
    } 

	//resultados
    public function getResultadosAttribute( $value) {

    	if(!is_null($value)){
    		return $value;
    	}

    	return '-';
    }

    //DATA TRATADA
    public function getDataTratadaAttribute($value){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataDataExcel($value);
        }
        return '-';
    } 

    //HORA TRATADA
    public function getHoraTratadaAttribute( $value ){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataHoraExcel($value);
        }
        return '-';
        
    } 

}
