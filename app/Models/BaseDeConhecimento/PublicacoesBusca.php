<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\RH\Funcionario;
use Carbon\Carbon;

class PublicacoesBusca extends Publicacao
{
    protected $table = 'publicacoes_busca';

    public function getDtPublicacaoAttribute( $value ) {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y');        
        }
        return $value;
    }

    public function getDtUltimaAtualizacaoAttribute( $value ) {
        if( !is_null($value)){
            return Carbon::parse($value)->format('d/m/Y');        
        }
        return $value;
    }

}
  
