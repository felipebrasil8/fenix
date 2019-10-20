<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseDeConhecimento\Publicacao;

class PublicacoesHistoricoBusca extends Publicacao
{
    protected $table = 'publicacoes_buscas_historicos';

    protected $fillable = array(
        'created_at',
        'usuario_id',
        'busca',
        'ip',
        'qtde_resultados',
        'resultados'
    ); 	


    public function setHistoricoBusca( $busca, $publicacoes ){
      
        $resultados = array();
       
        foreach ( $publicacoes->data  as $value) 
        {
            array_push( $resultados, [ 'id' => $value->id, 'nota' => $value->media_ponderada ]  );
        }
        $publicacoes = $publicacoes->toArray();
        PublicacoesHistoricoBusca::insert(
            [
                'usuario_id' => \Auth::user()->id,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'busca' => $busca, 
                'qtde_resultados' => $publicacoes['total'],
                'resultados' => json_encode($resultados),
                'pagina' => $publicacoes['current_page']
            ]
        );

    }

    public function getbuscas(){
        return PublicacoesHistoricoBusca::
            select('publicacoes_buscas_historicos.id')
            ->where('pagina', '=', '1' );
            

    }

}
