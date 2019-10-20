<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublicacoesRecomendacoes extends Model
{

    public $timestamps = false;

    protected $fillable = array(
        'id',
        'visualizada',
        'publicacao_id',
        'usuario_recomendado_id',
        'mensagem'
    );


    public function setRecomendacao( $recomendacao ){

        try{

        	return PublicacoesRecomendacoes::insertGetId(
                 [
                    'usuario_inclusao_id' => \Auth::user()->id,
                    'usuario_recomendado_id' => $recomendacao['funcionario_recomendado_id'],
                    'mensagem' => Str::upper($recomendacao['mensagem']),
                    'publicacao_id' => $recomendacao['publicacao_id']
                 ]
            );  

        }catch(\Exception $e ){
            throw new \Exception("Erro ao recomendar publicação", 1);
            
        }
    }

    public function getRecomendacoesPublicacao($de, $ate){

        return PublicacoesRecomendacoes::
            selectRaw('
                publicacoes_recomendacoes.created_at AS data, 
                publicacoes_recomendacoes.created_at AS hora,
                usuarios.nome as recomendou,
                tb.nome as recomendado,
                publicacoes.titulo,
                publicacoes_categorias.nome as categoria,
                (CASE WHEN pc.nome IS NULL THEN \'-\'
                    ELSE pc.nome
                END) as subcategoria,      
                publicacoes_recomendacoes.mensagem,
                (CASE WHEN publicacoes_recomendacoes.visualizada THEN \'SIM\'
                    ELSE \'NÃO\'
                END) as visualizada                
                ')
            ->join('usuarios', 'usuarios.id', '=', 'publicacoes_recomendacoes.usuario_inclusao_id')
            ->join('usuarios AS tb', 'tb.id', '=', 'publicacoes_recomendacoes.usuario_recomendado_id')
            ->join('publicacoes', 'publicacoes.id', '=', 'publicacoes_recomendacoes.publicacao_id')
            ->join('publicacoes_categorias', 'publicacoes_categorias.id', '=', 'publicacoes.publicacao_categoria_id')
            ->leftJoin('publicacoes_categorias AS pc', 'pc.id', '=', 'publicacoes_categorias.publicacao_categoria_id')
            ->whereDate('publicacoes_recomendacoes.created_at', '>=', $de)
            ->whereDate('publicacoes_recomendacoes.created_at', '<=', $ate)
            ->orderByDesc('publicacoes_recomendacoes.created_at')
            ->get();
    }


    public function getRecomendacaoPublicacao( $idPublicacao )
    {
        return PublicacoesRecomendacoes::where('publicacoes_recomendacoes.visualizada', '=', 'false')
            ->where('publicacoes_recomendacoes.publicacao_id', '=', $idPublicacao)
            ->where('publicacoes_recomendacoes.usuario_recomendado_id', '=', \Auth::user()->id)
            ->select('publicacoes_recomendacoes.id', 'usuarios.nome AS usuario', 'publicacoes_recomendacoes.mensagem')
            ->join('usuarios', 'usuarios.id', '=', 'publicacoes_recomendacoes.usuario_inclusao_id')
            ->orderBy('publicacoes_recomendacoes.created_at')
            ->first();
    }

    public function setVisualizada( $id )
    {
        try{
            PublicacoesRecomendacoes::where('id', '=', $id)->update(['visualizada' => true]);
        }catch(\Exception $e ){
            throw new \Exception("Erro ao setar visualização recomendação ".$e, 1);
        }
    }


    public function getRecomendadosPorPublicacao( $id ){

        return PublicacoesRecomendacoes::selectRaw('TO_CHAR(publicacoes_recomendacoes.created_at, \'dd/mm/YYYY HH24:mi:ss\') as data')            
            ->selectRaw('usuarios.nome')
            ->selectRaw('publicacoes_recomendacoes.mensagem')
            ->selectRaw('publicacoes_recomendacoes.visualizada')
            ->Join('usuarios', 'usuarios.id', '=', 'publicacoes_recomendacoes.usuario_recomendado_id')
            ->where('publicacoes_recomendacoes.usuario_inclusao_id', \Auth::user()->id )
            ->where('publicacoes_recomendacoes.publicacao_id', $id)
            ->orderBy('publicacoes_recomendacoes.created_at', 'desc')
            ->get();        
    }

}

