<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\BaseDeConhecimento\PublicacaoColaborador;
use App\Models\BaseDeConhecimento\PublicacaoVisualizacao;
use App\Models\BaseDeConhecimento\PublicacaoMensagem;
use App\Models\BaseDeConhecimento\PublicacaoHistorico;
use App\Models\BaseDeConhecimento\PublicacaoCategoria;

class PublicacaoDashboard extends Publicacao
{
   
    private $filtro;
    private $filtroDe;
    private $filtroAte;

    public function setPeriodoFiltro($request){
        $this->filtro = $request->selected_date;
        $this->filtroDe = $request->selected_date_de;
        $this->filtroAte = $request->selected_date_ate;
    }

    public function getTotalPublicacoesPublicadas(){
        return $this->queryPublicacao( false, false)
                ->count();
    }

    public function getTotalColaboradoresPublicadas(){
        $publicacaoColaborador = new PublicacaoColaborador;
        return $publicacaoColaborador->queryColaborador( )
                ->where('rascunho', '=', false)
               ->count();
    }

    public function getTotalAcessoPeriodo(){
        $publicacaoVisualizacao = new PublicacaoVisualizacao;
        return $publicacaoVisualizacao->getVisualizacoes( )
            ->whereRaw( $this->dateFilter('publicacoes_visualizacoes.created_at') )
            ->count();
    }

    public function getTotalPesquisaPeriodo(){
        $publicacoesHistoricoBusca = new PublicacoesHistoricoBusca;
        return $publicacoesHistoricoBusca->getbuscas()
            ->whereRaw( $this->dateFilter('publicacoes_buscas_historicos.created_at') )
            ->count();
    }

    public function getTotalPesquisaSemResultadoPeriodo(){
        $publicacoesHistoricoBusca = new PublicacoesHistoricoBusca;
        return $publicacoesHistoricoBusca->getbuscas()
            ->where('qtde_resultados', '=', 0)
            ->where('tratada', '=', false)
            ->count();
    }

    public function getBuscasSemResultado(){
        $publicacoesHistoricoBusca = new PublicacoesHistoricoBusca;
        return $publicacoesHistoricoBusca::
            selectRaw('TO_CHAR(min(publicacoes_buscas_historicos.created_at), \'DD/MM/YYYY HH24:MI:SS\') as min , usuarios.nome, publicacoes_buscas_historicos.busca, count(publicacoes_buscas_historicos.id),  usuarios.id ')
            ->where('qtde_resultados', '=', 0)
            ->where('tratada', '=', false)
            ->leftJoin( 'usuarios', 'usuarios.id', '=', 'publicacoes_buscas_historicos.usuario_id' )
            ->groupBy( 'usuarios.nome', 'publicacoes_buscas_historicos.busca', 'usuarios.id' )  
            ->orderByRaw('min(publicacoes_buscas_historicos.created_at)')
            ->get();
    }

    public function getTotalNovasPublicacoesPeriodo(){
        return Publicacao::select( 'publicacoes.id' )
            ->whereRaw( $this->dateFilter('publicacoes.dt_publicacao') )
            ->count();
    }

    public function getTotalNovasPublicacoesCategoriaPeriodo(){
            $sql = PublicacaoCategoria::
            selectRaw(  'count( publicacoes.id ) as valor, publicacoes_categorias.publicacao_categoria_id' )  
            ->whereRaw('publicacoes_categorias.publicacao_categoria_id IS NOT NULL')
            ->leftJoin('publicacoes', function ($join){                            
                $join->on('publicacoes.publicacao_categoria_id', '=', 'publicacoes_categorias.id')
               ->whereRaw( $this->dateFilter('publicacoes.dt_publicacao') );
            })
            ->groupBy('publicacoes_categorias.publicacao_categoria_id')
            ->orderBy('valor', 'desc');
       
        return PublicacaoCategoria::
            selectRaw(  'publicacoes_categorias.nome, COALESCE(tb.valor, 0) + count( publicacoes.id ) as valor' )  
            ->leftjoin( \DB::raw( "( ".$sql->toSql().") tb "), 'tb.publicacao_categoria_id', '=', 'publicacoes_categorias.id' )
            ->leftJoin('publicacoes', function ($join){                            
                $join->on('publicacoes.publicacao_categoria_id', '=', 'publicacoes_categorias.id')
                ->whereRaw( $this->dateFilter('publicacoes.dt_publicacao') );
            })
            ->whereRaw('publicacoes_categorias.publicacao_categoria_id IS NULL')
            ->mergeBindings($sql->getQuery())
            ->groupBy('publicacoes_categorias.nome')
            ->groupBy('tb.valor')
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->get();


    }
    public function getTotalPublicacoesAtualizadasPeriodo(){
        return PublicacaoHistorico::select( 'publicacoes_historicos.id' )
            ->where('publicacoes_historicos.alteracao', '=', 'ATUALIZACAO')
            ->whereRaw( '(SPLIT_PART(SPLIT_PART( publicacoes_historicos.movimentacao::text,\',\',3 ),\':\',2 ) != \'""\')')
            ->whereRaw( $this->dateFilter('SPLIT_PART(SPLIT_PART( publicacoes_historicos.movimentacao::text,\',\',3 ),\':\',2 )::date') )
            ->count();
    }

    public function getTotalAtualizadasPublicacoesCategoriaPeriodo(){
           
        $sql = PublicacaoCategoria::
            selectRaw(  'count( publicacoes_historicos.id ) as valor, publicacoes_categorias.publicacao_categoria_id' )  
            ->whereRaw('publicacoes_categorias.publicacao_categoria_id IS NOT NULL')
            ->leftJoin('publicacoes', function ($join){                            
                $join->on('publicacoes.publicacao_categoria_id', '=', 'publicacoes_categorias.id');
            })
            ->leftJoin('publicacoes_historicos', function ($join){                            
                $join->on('publicacoes_historicos.publicacao_id', '=', 'publicacoes.id')
                ->where('publicacoes_historicos.alteracao', '=', 'ATUALIZACAO')
                ->whereRaw( '(SPLIT_PART(SPLIT_PART( publicacoes_historicos.movimentacao::text,\',\',3 ),\':\',2 ) != \'""\')')
                ->whereRaw( $this->dateFilter('SPLIT_PART(SPLIT_PART( publicacoes_historicos.movimentacao::text,\',\',3 ),\':\',2 )::date') );
            })
            ->groupBy('publicacoes_categorias.publicacao_categoria_id')
            ->orderBy('valor', 'desc');
       
        return PublicacaoCategoria::
            selectRaw(  'publicacoes_categorias.nome, COALESCE(tb.valor, 0) + count( publicacoes_historicos.id ) as valor' )  
            ->leftjoin( \DB::raw( "( ".$sql->toSql().") tb "), 'tb.publicacao_categoria_id', '=', 'publicacoes_categorias.id' )
            ->leftJoin('publicacoes', function ($join){                            
                $join->on('publicacoes.publicacao_categoria_id', '=', 'publicacoes_categorias.id');
            })
            ->leftJoin('publicacoes_historicos', function ($join){                            
                 $join->on('publicacoes_historicos.publicacao_id', '=', 'publicacoes.id')
                ->where('publicacoes_historicos.alteracao', '=', 'ATUALIZACAO')
                ->whereRaw( '(SPLIT_PART(SPLIT_PART( publicacoes_historicos.movimentacao::text,\',\',3 ),\':\',2 ) != \'""\')')
                ->whereRaw( $this->dateFilter('SPLIT_PART(SPLIT_PART( publicacoes_historicos.movimentacao::text,\',\',3 ),\':\',2 )::date') );
            })
            ->whereRaw('publicacoes_categorias.publicacao_categoria_id IS NULL')
            ->mergeBindings($sql->getQuery())
            ->groupBy('publicacoes_categorias.nome')
            ->groupBy('tb.valor')
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->get();


    }

    public function getTotalAcessoUsuarioPeriodo(){
        $publicacaoVisualizacao = new PublicacaoVisualizacao;
        $value = $publicacaoVisualizacao->getVisualizacoes()
               ->whereRaw( $this->dateFilter('publicacoes_visualizacoes.created_at') )

               ->first();

        if ($value != null ){
            return $value->count;
        }
        return 0;
    }

    public function getTotalMensagensRecebidasPeriodo(){
        $publicacaoMensagem = new PublicacaoMensagem();
        return $publicacaoMensagem->queryMensagens()
            ->whereRaw( $this->dateFilter('publicacoes_mensagens.created_at') )
            ->count();
    }

    public function getTotalMensagensRecebidasNaoRespondidasPeriodo(){
        $publicacaoMensagem = new PublicacaoMensagem();
        return $publicacaoMensagem->queryMensagens()
            ->where('respondida', '=', false)
            ->count();
        
    }

    public function getPesquisasMaisRealizadasPeriodo( $limite = true ){
        return PublicacoesHistoricoBusca::
            selectRaw('publicacoes_buscas_historicos.busca as nome, count(publicacoes_buscas_historicos.busca) as valor' )
            ->where('publicacoes_buscas_historicos.pagina', '=', '1' )
            ->groupBy('publicacoes_buscas_historicos.busca')
            ->whereRaw( $this->dateFilter('publicacoes_buscas_historicos.created_at') )
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->queryLimite(5, $limite)
            ->get();
    }

  

    public function getPesquisasPorDepartamentoPeriodo( $limite = true ){
        return PublicacoesHistoricoBusca::
            selectRaw('departamentos.nome as nome, areas.nome as area, count(departamentos.nome) as valor' )
            ->leftJoin( 'usuarios', 'usuarios.id', '=', 'publicacoes_buscas_historicos.usuario_id' )
            ->leftJoin( 'funcionarios', 'funcionarios.id', '=', 'usuarios.funcionario_id' )
            ->leftJoin( 'cargos', 'cargos.id', '=', 'funcionarios.cargo_id' )
            ->leftJoin( 'departamentos', 'departamentos.id', '=', 'cargos.departamento_id' )
            ->leftJoin( 'areas', 'areas.id', '=', 'departamentos.area_id' )
            ->where('publicacoes_buscas_historicos.pagina', '=', '1' )
            ->groupBy('departamentos.nome')
            ->groupBy('area')
            ->whereRaw( $this->dateFilter('publicacoes_buscas_historicos.created_at') )
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->queryLimite(5, $limite)
            ->get();
    }      

    public function getPesquisasPorUsuarioPeriodo( $limite = true ){
        return PublicacoesHistoricoBusca::
            selectRaw('funcionarios.nome as nome,departamentos.nome as departamento, areas.nome as area, count(funcionarios.nome) as valor, funcionarios.id as avatar' )
            ->leftJoin( 'usuarios', 'usuarios.id', '=', 'publicacoes_buscas_historicos.usuario_id' )
            ->leftJoin( 'funcionarios', 'funcionarios.id', '=', 'usuarios.funcionario_id' )
            ->leftJoin( 'cargos', 'cargos.id', '=', 'funcionarios.cargo_id' )
            ->leftJoin( 'departamentos', 'departamentos.id', '=', 'cargos.departamento_id' )
            ->leftJoin( 'areas', 'areas.id', '=', 'departamentos.area_id' )
            ->where('publicacoes_buscas_historicos.pagina', '=', '1' )
            ->groupBy('funcionarios.nome')
            ->groupBy('funcionarios.id')
            ->groupBy('departamento')
            ->groupBy('area')
            ->whereRaw( $this->dateFilter('publicacoes_buscas_historicos.created_at') )
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->queryLimite(5, $limite)
            ->get();
    }

    public function getPodioColaborador( $limite = true ){
        // $publicacaoColaborador = new PublicacaoColaborador;
        return PublicacaoColaborador::
            selectRaw( 'funcionarios.nome as nome,departamentos.nome as departamento, areas.nome as area, count(funcionarios.nome) as valor, funcionarios.id as avatar' )
            ->leftJoin('publicacoes', 'publicacoes.id', '=', 'publicacoes_colaboradores.publicacao_id' )
            ->leftJoin('funcionarios', 'funcionarios.id', '=', 'publicacoes_colaboradores.funcionario_id' )
            ->leftJoin( 'cargos', 'cargos.id', '=', 'funcionarios.cargo_id' )
            ->leftJoin( 'departamentos', 'departamentos.id', '=', 'cargos.departamento_id' )
            ->leftJoin( 'areas', 'areas.id', '=', 'departamentos.area_id' )
            ->groupBy('funcionarios.nome')
            ->groupBy('funcionarios.id')
            ->groupBy('departamento')
            ->groupBy('area')
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->where('publicacoes_colaboradores.rascunho', '=', false)
            ->queryWherePublicada()
            ->queryLimite(5, $limite)
            ->get();
    }

    public function getPublicacoesCategoria( $limite = true ){
        $sql = PublicacaoCategoria::
            selectRaw(  'count( publicacoes.id ) as valor, publicacoes_categorias.publicacao_categoria_id' )  
            ->whereRaw('publicacoes_categorias.publicacao_categoria_id IS NOT NULL')
            ->queryJoinWherePublicada()
            ->groupBy('publicacoes_categorias.publicacao_categoria_id')
            ->orderBy('valor', 'desc');
       
        return PublicacaoCategoria::
            selectRaw(  'publicacoes_categorias.nome, COALESCE(tb.valor, 0) + count( publicacoes.id ) as valor' )  
            ->leftjoin( \DB::raw( "( ".$sql->toSql().") tb "), 'tb.publicacao_categoria_id', '=', 'publicacoes_categorias.id' )
            ->queryJoinWherePublicada()
            ->whereRaw('publicacoes_categorias.publicacao_categoria_id IS NULL')
            ->mergeBindings($sql->getQuery())
            ->groupBy('publicacoes_categorias.nome')
            ->groupBy('tb.valor')
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->queryLimite(7, $limite)
            ->get();
    }

    public function getPublicacoesCategoriaSubcategoria( ){
        
        $sql = PublicacaoCategoria::
            selectRaw( 'publicacoes_categorias.nome, count( publicacoes.id ) as valor, publicacoes_categorias.publicacao_categoria_id' )  
            ->whereRaw('publicacoes_categorias.publicacao_categoria_id IS NOT NULL')
            ->queryJoinWherePublicada()
            ->groupBy('publicacoes_categorias.publicacao_categoria_id')
            ->groupBy('publicacoes_categorias.nome')
            ->orderBy('valor', 'desc');
       
        return PublicacaoCategoria::
            selectRaw(  'tb.nome sub, publicacoes_categorias.nome categoria, COALESCE(tb.valor, 0) + count( publicacoes.id ) as valor' )  
            ->leftjoin( \DB::raw( "( ".$sql->toSql().") tb "), 'tb.publicacao_categoria_id', '=', 'publicacoes_categorias.id' )
            ->queryJoinWherePublicada()
            ->whereRaw('publicacoes_categorias.publicacao_categoria_id IS NULL')
            ->mergeBindings($sql->getQuery())
            ->groupBy('tb.nome')
            ->groupBy('publicacoes_categorias.nome')
            ->groupBy('tb.valor')
            ->orderBy('valor', 'desc')
            ->orderBy('sub')
            ->orderBy('categoria')
            ->get();
    }

    public function getMaisAcessadasPeriodo( $limite = true ){

        return PublicacaoVisualizacao::
            selectRaw('publicacoes.titulo as nome, count(publicacoes.id) as valor')
            ->leftJoin('publicacoes', 'publicacoes.id', '=', 'publicacoes_visualizacoes.publicacao_id' )
            ->whereRaw( $this->dateFilter('publicacoes_visualizacoes.created_at') )
            ->groupBy('publicacoes.titulo', 'publicacoes.id')
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->queryLimite(7, $limite)
            ->get();
    }

    public function getMaisAcessadasPeriodoExcel(){

        $sql = PublicacaoCategoria::
            selectRaw( 'publicacoes_categorias.nome as categoria, publicacoes_categorias.id' );

        return PublicacaoVisualizacao::
            selectRaw(' publicacoes.id, 
                        publicacoes.titulo as titulo, 
                        publicacoes_categorias.nome as categoria, 
                        tb.categoria as sub, 
                        count(publicacoes.id) as valor')
            
            ->leftJoin('publicacoes', 'publicacoes.id', '=', 'publicacoes_visualizacoes.publicacao_id' )
            ->leftJoin('publicacoes_categorias', 'publicacoes_categorias.id', '=', 'publicacoes.publicacao_categoria_id' )
            
            ->leftjoin( \DB::raw( "( ".$sql->toSql().") tb "), 'tb.id', '=', 'publicacoes_categorias.publicacao_categoria_id' )
            
            ->whereRaw( $this->dateFilter('publicacoes_visualizacoes.created_at') )
            ->groupBy('publicacoes.titulo')
            ->groupBy('publicacoes.id')
            ->groupBy('publicacoes_categorias.nome')
            ->groupBy('tb.categoria')
            ->orderBy('valor', 'desc')
            ->orderBy('nome')
            ->get();
    }


    private function dateFilter( $column = 'publicacoes.dt_publicacao' )
    {
       
        if( $this->filtro == 'hoje' )
        {
            return "TO_CHAR(".$column.", 'DD-MM-YYYY') = TO_CHAR(NOW(), 'DD-MM-YYYY')";
        }
        if( $this->filtro == 'ultimo_dia' )
        {
            return "CASE WHEN TO_CHAR(NOW(), 'D') = '1' THEN TO_CHAR(".$column.", 'DD-MM-YYYY') = TO_CHAR(NOW() - '2 day'::INTERVAL, 'DD-MM-YYYY')
                         WHEN TO_CHAR(NOW(), 'D') = '2' THEN TO_CHAR(".$column.", 'DD-MM-YYYY') = TO_CHAR(NOW() - '3 day'::INTERVAL, 'DD-MM-YYYY')
                         ELSE TO_CHAR(".$column.", 'DD-MM-YYYY') = TO_CHAR(NOW() - '1 day'::INTERVAL, 'DD-MM-YYYY')
                    END";
        }
        else if( $this->filtro == 'semana_atual' )
        {
            return "TO_CHAR(".$column.",  'ww') = TO_CHAR(NOW(), 'ww') AND TO_CHAR(".$column.",  'YYYY') = TO_CHAR(NOW(), 'YYYY')";
        }
        else if( $this->filtro == 'ultima_semana' )
        {
            return "TO_CHAR(".$column.",  'ww')::int = ( TO_CHAR(NOW(),  'ww')::int  - 1::int )::int AND TO_CHAR(".$column.",  'YYYY') = TO_CHAR(NOW(), 'YYYY')";
        }
        else if( $this->filtro == 'mes_atual' )
        {
            return "TO_CHAR(".$column.", 'MM-YYYY') = TO_CHAR(NOW(), 'MM-YYYY')";
        }
        else if( $this->filtro == 'ultimo_mes' )
        {
            return "TO_CHAR(".$column.", 'MM-YYYY') = TO_CHAR( NOW() - '1 month'::INTERVAL , 'MM-YYYY')";
        }
        else if( $this->filtro == 'ultimos_trinta_dias' )
        {
            return "".$column."::DATE >= (NOW() - '1 month'::INTERVAL)::DATE";
        }
        else if( $this->filtro == 'ano_atual' )
        {
            return "TO_CHAR(".$column.", 'YYYY') = TO_CHAR(NOW(), 'YYYY')";
        }
        else if( $this->filtro == 'ultimo_ano' )
        {
            return "".$column."::DATE >= (NOW() - '1 year'::INTERVAL)::DATE";
        }
        else if(  $this->filtro == 'customizado' )
        {
            if( isset( $this->filtroDe ) && isset( $this->filtroAte ) )
            {
                return "".$column."::DATE BETWEEN '".$this->filtroDe."'::DATE AND '".$this->filtroAte."'::DATE";
            }
        }

        return "FALSE";
    }


                


}
