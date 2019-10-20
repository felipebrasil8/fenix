<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BaseDeConhecimento\Publicacao;


class PublicacaoVisualizacao extends Publicacao
{

    protected $table = 'publicacoes_visualizacoes';

    protected $fillable = array(
      
        'id',
        'pagina_anterior',
        'browser',
        'so',
        'usuario_inclusao_id'        
    );

    protected $hidden = array(
        'created_at',        
        'publicacao_id',
        'delete_at'
    );

    public function setVisualizacao($publicacao_id, $usuario_inclusao_id){
        
                PublicacaoVisualizacao::insert(
             [
                'usuario_inclusao_id' => $usuario_inclusao_id, 
                'publicacao_id' => $publicacao_id,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'pagina_anterior' => isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'DIRECT',
                'browser' => \Browser::browserFamily(),
                'so' => \Browser::platformFamily()
             ]
        );

    }

    public function getVisualizacoesPublicacao( $id )    
    {
        $from = PublicacaoVisualizacao::selectRaw('publicacoes_visualizacoes.usuario_inclusao_id')
            ->join('publicacoes', 'publicacoes_visualizacoes.publicacao_id', '=', 'publicacoes.id')
            ->where('publicacoes_visualizacoes.publicacao_id', '=', $id)
            ->whereRaw('publicacoes_visualizacoes.created_at::DATE >= publicacoes.dt_publicacao::DATE')
            ->where(function( $query ){
                $query->whereRaw('publicacoes_visualizacoes.created_at::DATE < publicacoes.dt_desativacao::DATE')
                ->orWhereNull( 'publicacoes.dt_desativacao' );  
            }) 
            ->groupBy(DB::raw('publicacoes_visualizacoes.publicacao_id, publicacoes_visualizacoes.usuario_inclusao_id, publicacoes_visualizacoes.created_at::DATE'));

        $count = DB::table( DB::raw("({$from->toSql()}) as sub") )
            ->mergeBindings($from->getQuery())
            ->count();

        return $count;
    }


    public function getVisualizacoes()    
    {
        return PublicacaoVisualizacao::
            selectRaw('count(DISTINCT publicacoes_visualizacoes.usuario_inclusao_id)');
            

        // $count = DB::table( DB::raw("({$from->toSql()}) as sub") )
        //     ->mergeBindings($from->getQuery())
        //     ->count();

        // return $count;
    }


    public function getVisualizacoesPublicacaoExportacao($de, $ate)
    {
        return PublicacaoVisualizacao::
            selectRaw('
                publicacoes_visualizacoes.created_at AS data, 
                publicacoes_visualizacoes.created_at AS hora,
                usuarios.nome AS usuario,
                (CASE WHEN departamentos.nome IS NULL THEN \'-\'
                    ELSE departamentos.nome
                END) as departamento, 
                (CASE WHEN areas.nome IS NULL THEN \'-\'
                    ELSE areas.nome
                END) as area, 
                publicacoes.titulo,
                publicacoes_categorias.nome as categoria,
                (CASE WHEN pc.nome IS NULL THEN \'-\'
                    ELSE pc.nome
                END) as subcategoria,      
                publicacoes_visualizacoes.ip,
                publicacoes_visualizacoes.pagina_anterior,
                publicacoes_visualizacoes.browser,
                publicacoes_visualizacoes.so
                ')
            ->join('usuarios', 'usuarios.id', '=', 'publicacoes_visualizacoes.usuario_inclusao_id')
            ->join('publicacoes', 'publicacoes.id', '=', 'publicacoes_visualizacoes.publicacao_id')
            ->join('publicacoes_categorias', 'publicacoes_categorias.id', '=', 'publicacoes.publicacao_categoria_id')
            ->leftJoin('publicacoes_categorias AS pc', 'pc.id', '=', 'publicacoes_categorias.publicacao_categoria_id')
            ->leftJoin('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
            ->leftJoin('cargos', 'funcionarios.cargo_id', '=', 'cargos.id')
            ->leftJoin('departamentos', 'cargos.departamento_id', '=', 'departamentos.id')
            ->leftJoin('areas', 'areas.id', '=', 'departamentos.area_id')
            ->whereDate('publicacoes_visualizacoes.created_at', '>=', $de)
            ->whereDate('publicacoes_visualizacoes.created_at', '<=', $ate)
            ->orderByDesc('publicacoes_visualizacoes.created_at')
            ->get();
    }

    public function getPesquisaVisualizacaoPublicacao( $id )
    {
        return PublicacaoVisualizacao::selectRaw('
                publicacoes_visualizacoes.created_at AS data, 
                usuarios.nome AS usuario,
                (CASE WHEN departamentos.nome IS NULL THEN \'-\'
                    ELSE departamentos.nome
                END) as departamento, 
                (CASE WHEN areas.nome IS NULL THEN \'-\'
                    ELSE areas.nome
                END) as area' )
            ->join('publicacoes', 'publicacoes_visualizacoes.publicacao_id', '=', 'publicacoes.id')
            ->join('usuarios', 'usuarios.id', '=', 'publicacoes_visualizacoes.usuario_inclusao_id')
            ->leftJoin('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
            ->leftJoin('cargos', 'funcionarios.cargo_id', '=', 'cargos.id')
            ->leftJoin('departamentos', 'cargos.departamento_id', '=', 'departamentos.id')
            ->leftJoin('areas', 'areas.id', '=', 'departamentos.area_id')
            ->where('publicacoes_visualizacoes.publicacao_id', '=', $id)
            ->whereRaw('publicacoes_visualizacoes.created_at::DATE >= publicacoes.dt_publicacao::DATE')
            ->where(function( $query ){
                $query->whereRaw('publicacoes_visualizacoes.created_at::DATE < publicacoes.dt_desativacao::DATE')
                ->orWhereNull( 'publicacoes.dt_desativacao' );  
            }) 
            ->groupBy(DB::raw('publicacoes_visualizacoes.publicacao_id, usuarios.nome, to_char(publicacoes_visualizacoes.created_at, \'dd/mm/YYYY\'), departamentos.nome, areas.nome'))
            ->orderByRaw('to_char(publicacoes_visualizacoes.created_at, \'dd/mm/YYYY\')::date desc' )
            ->get();

    }

}
