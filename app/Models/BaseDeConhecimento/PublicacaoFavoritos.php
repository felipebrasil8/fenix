<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseDeConhecimento\Publicacao;
use Carbon\Carbon;

class PublicacaoFavoritos extends Publicacao
{
    public $timestamps = false;

   protected $table = 'publicacoes_favoritos';

  
    protected function menorOuIgualAgora( $data ){
        return Carbon::createFromFormat('d/m/Y',$data)->lte(Carbon::now());
    }

}
