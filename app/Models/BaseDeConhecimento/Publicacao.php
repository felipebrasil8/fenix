<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;

use App\Models\BaseDeConhecimento\PublicacaoCategoria;
use App\Models\BaseDeConhecimento\PublicacaoTag;
use App\Models\BaseDeConhecimento\PublicacaoCargo;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Util\Date;

class Publicacao extends Model
{   

    protected $table = 'publicacoes';

    protected $fillable = array(
      
        'created_at',
        'updated_at',
        'usuario_inclusao_id',
        'usuario_alteracao_id',
        'publicacao_categoria_id',
        'ativo',
        'titulo',
        'resumo',
        'imagem',
        'dt_publicacao',
        'dt_ultima_atualizacao',
        'dt_desativacao',
        'lista_relacionados',
        'restricao_acesso',
        'dt_revisao'
    );

    protected $hidden = array(
        'usuario_inclusao_id',
        'usuario_alteracao_id',
        'created_at',
        'updated_at',
        'ativo',
    );
   
    private $orderBy = 'dt_publicacao';

    protected $tipoPublicacao;

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
    public function getDtDesativacaoAttribute( $value ) {
        if( !is_null($value)){
            return Carbon::parse($value)->format('d/m/Y');        
        }
        return $value;
    }

    public function getDtRevisaoAttribute( $value ) {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y');        
        }
        return $value;
    }

    public function getTituloAttribute( $value ) {
        return strtoupper($value);
    }
    
    public function setOrderBy($orderBy){
        $this->orderBy = $orderBy;
    }

    private function getOrderBy(){
        return $this->orderBy;
    }    

    /**
     * [getPublicacoes description]
     * @param  [integer]    $id        [id da categoria na publicação]
     * @return [Collection] Collection [retorna a Collection de Publicacao]
     */
    public function getPublicacoes($id){

        return Publicacao::
            where('publicacao_categoria_id', '=', $id)
            ->select('id' ,'titulo', 'resumo')
            ->orderBy( $this->getOrderBy() )
            ->get();

    }       

    public function getCategoriaId($id){

        return Publicacao::whereId($id)
            ->select('publicacao_categoria_id')
            ->first();
    }

    public function getPublicacao($id){

        return Publicacao::where('publicacoes.id', '=', $id)            
            ->queryJoinPublicacaoFavoritos()
            ->select('publicacoes.lista_relacionados', 'publicacoes.restricao_acesso')
            ->selectRaw( $this->selectBusca() )
            ->first();
    }

    public function getPublicacaoDatas($id){
        
       return Publicacao::whereId($id)
            ->select('id', 'dt_publicacao', 'dt_ultima_atualizacao', 'dt_desativacao', 'dt_revisao')
            ->first();

    }

    public function getPublicacoesRelacionadas( $publicacao )
    {
        if( $publicacao->lista_relacionados == 0 )
        {
            return '';
        }

        $tag = new PublicacaoTag();
        $busca = new PublicacoesBusca();

        $tags = $tag->getTagsPublicacao( $publicacao->id );

        return $this->getPublicacoesBuscaRelacionadas( $publicacao->titulo.' '.str_replace("\n", ' ', $publicacao->resumo).(count($tags) > 0 ? ' '.$tags->implode('tag', ' ') : ''), $publicacao->id, $publicacao->lista_relacionados );
    }

   
    /**
     * [formataData Formata os campos de data para exibir na tela a data e o tooltip correto]
     * @param  [collection] $publicacoes [ Collection com as publicações ( Ativadas, Desativadas ou Não Publicadas)]
     * @return [collection]              [description]
     */
    protected function formataData( $publicacoes ){        

        return $publicacoes->map( function( $item, $key ){
                
            if( ( !is_null( $item->dt_ultima_atualizacao ) || !empty( $item->dt_ultima_atualizacao ) ) && $this->menorOuIgualAgora($item->dt_ultima_atualizacao) )
            {

                $item->__set('tooltip', 'Data de atualização');
                $item->__set('data', $item->dt_ultima_atualizacao);
                
            }

            else if( ( !is_null( $item->dt_publicacao ) || !empty( $item->dt_publicacao )) && $this->menorOuIgualAgora($item->dt_publicacao) )
            {

                $item->__set('tooltip', 'Data de publicação');
                $item->__set('data', $item->dt_publicacao);
                
            }
            else if ( ( !is_null( $item->dt_desativacao ) || !empty( $item->dt_desativacao )) && $this->menorOuIgualAgora($item->dt_desativacao) ) 
            {

                $item->__set('tooltip', 'Data de desativação');
                $item->__set('data', $item->dt_desativacao);

            }
            else
            {

                $item->__set('tooltip', 'Não publicado');
                $item->__set('data', 'Não publicado');

            } 
     

            $item->__unset('dt_publicacao');
            $item->__unset('dt_ultima_atualizacao');
            $item->__unset('dt_desativacao');
          
            return $item;
            
        });

    }


    /**
     * [selectBusca description]
     * @param  boolean $favoritos [description]
     * @return [type]             [description]
     */
    protected function selectBusca( $favoritos = true )
    {
        if( $favoritos )
        {
            return 'publicacoes.id, publicacoes.titulo, publicacoes.resumo, publicacoes.dt_publicacao, publicacoes.dt_ultima_atualizacao, CASE WHEN publicacoes_favoritos.publicacao_id is null THEN false else true END as favorito, publicacoes_favoritos.id as id_favorito, COALESCE((select count(publicacao_id) from publicacoes_visualizacoes where usuario_inclusao_id = '. \Auth::user()->id .' and publicacao_id = publicacoes.id group by publicacao_id), 0) as contador';
        }

        return 'publicacoes.id, publicacoes.titulo, publicacoes.resumo, publicacoes.dt_publicacao, publicacoes.dt_ultima_atualizacao';
    }

    protected function menorOuIgualAgora( $data ){
        return Carbon::createFromFormat('d/m/Y',$data)->lte(Carbon::now());
    }
    
    public function getImagemPublicacaoBase( $id ){
        
        return Publicacao::where('id','=', $id)
            ->select('imagem')
            ->first()
            ->imagem;
    }

    public function updateRestricaoAcesso( $publicacao_id, $restricaoAcesso )
    {
        Publicacao::where('id', $publicacao_id)
            ->update( 
             [
                'usuario_alteracao_id' => \Auth::user()->id,
                'restricao_acesso' => $restricaoAcesso
             ]
        );
    }

    public function getFavoritos()
    {
        $this->tipoPublicacao = 'favoritos';

        return $this->formataData($this->queryPublicacao()
            ->join('publicacoes_favoritos', function ($join) {                            
                $join->on('publicacoes.id', '=', 'publicacoes_favoritos.publicacao_id')
                ->where('publicacoes_favoritos.usuario_id', '=', \Auth::id());
            })
            ->get());
    }



    public function getPublicacoesUltimos30Dias()
    {
        return $this->formataProximasPublicacoes($this->queryPublicacao()
            ->whereRaw('max_date( ( CASE WHEN dt_ultima_atualizacao::DATE > CURRENT_DATE THEN dt_publicacao ELSE dt_ultima_atualizacao END ), dt_publicacao )::date >= (NOW() - INTERVAL \'30 day\')::date')
            ->queryJoinPublicacaoFavoritos()
            ->get(), 'novas');
   
    }

    public function getProximasPublicacoes()
    {
        return Publicacao::
            whereNull( 'dt_publicacao' )
            ->orWhere(function( $query ){
                $query->whereDate( 'dt_publicacao', '>', date('d/m/Y') );
            })
            ->orWhere(function( $query ){
                $query->whereDate('dt_ultima_atualizacao', '>', date('d/m/Y'))
                        ->whereNotNull( 'dt_publicacao' );
            })            
            ->selectRaw( $this->selectBusca( false ) )
            ->orderByRaw('dt_publicacao::DATE, id')
            //->toSql();
            ->get();
    }

    /*Criar nova tela para listar as publicações não publicadas: 
    - listar todas as publicações cujo a data de publicação ou data de atualização sejam maiores do que a data atual (ok)
    - as publicações devem ser agrupadas por essa data (atualização ou publicação) (php)
    - trazer antes do agrupamento por data, um agrupamento das publicações que não possuem data de publicação (php)
    - caso as duas datas sejam maiores do que hoje, considerar a data menor (php)
    - ordenar de forma crescente (primeiro as datas mais próximas) e desempatar pelo ID  ok
    - mostrar a quantidade total no topo da página 
    - essa tela só pode ser aberta no modo de edição ativado (redirecionar para /base-de-conhecimento/publicacoes) (ok)
    - na caixa da publicação, ao invés de trazer a data, mostrar se é Publicação nova ou Atualização 
    - trocar o nome do botão de VEJA! para EDITAR 
    - quando clicar nas publicações, direcionar diretamente para o modo de edição da publicação (/edit) 
    - lembrar de mudar todos os links do componente da publicação para a tela de edição 
    - lembrar de remover o tooltip da data no componente, pois será vazio*/

    /**
     * [getPublicacoesDesativadas - Busca as publicacoes desativadas, ordenando descendente os campos de data de atualização e data de publicação]
     * @param  [integer]    $id        [id da categoria na publicação]
     * @return [Collection] Collection [retorna a Collection de Publicacao]
     */
    public function getPublicadas( $id ){
        
         return $this->formataData($this->queryPublicacao()
            ->where('publicacao_categoria_id', '=', $id)
            ->queryJoinPublicacaoFavoritos()
            ->get());

    }

    public function queryPublicacao( $isBusca = false, $filtroCargo = true) 
    {
        return Publicacao::
            selectRaw( $this->selectBusca() )
            ->queryWherePublicada()
            ->queryWherePublicadaCargo( $filtroCargo )
            ->queryOrderByPublicacao( $isBusca );
    } 

    public function scopeQueryWherePublicada( $query )
    {
        return $query->where(function( $query ){
                $query->whereDate('publicacoes.dt_publicacao', '<=', date('d/m/Y'))
                ->whereNotNull('publicacoes.dt_publicacao');
            })            
            ->where(function( $query ){
                $query->whereDate('publicacoes.dt_desativacao', '>', date('d/m/Y'))
                ->orWhereNull('publicacoes.dt_desativacao');                
            });
    } 
 
 
    public function scopeQueryJoinWherePublicada( $query )
    {
        return $query->leftJoin('publicacoes', function ($join) use ($query) {                            
                $join->on('publicacoes.publicacao_categoria_id', '=', 'publicacoes_categorias.id')
                ->where(function( $query ){
                    $query->whereDate('publicacoes.dt_publicacao', '<=', date('d/m/Y'))
                    ->whereNotNull('publicacoes.dt_publicacao');
                })            
                ->where(function( $query ){
                    $query->whereDate('publicacoes.dt_desativacao', '>', date('d/m/Y'))
                    ->orWhereNull('publicacoes.dt_desativacao');                
                });
            });

           
    } 
    
    

    public function scopeQueryJoinPublicacaoFavoritos( $query )
    {
        return $query->leftJoin('publicacoes_favoritos', function ($join) {                            
                $join->on('publicacoes.id', '=', 'publicacoes_favoritos.publicacao_id')
                ->where('publicacoes_favoritos.usuario_id', '=', \Auth::id());
            });
    } 

    public function scopeQueryOrderByPublicacao( $query, $isBusca )
    {
        if( $this->tipoPublicacao != '' && $this->tipoPublicacao == 'favoritos' ){            
            
            $query->orderByRaw(' contador desc');
            $query->orderByRaw(' publicacoes_favoritos.created_at desc');
            
            return $query;
        }

        if( $isBusca ){
            return $query->orderBy('media_ponderada', 'desc');
        }
        return $query->orderByRaw('max_date( publicacoes.dt_ultima_atualizacao, publicacoes.dt_publicacao ) desc');
            

    } 

    public function scopeQueryLimite( $query, $limite, $limitar = true  )
    {
        if($limitar == true ){
           return $query->limit($limite);
        }
            // return $query->limit($limite);
            // return $query->where('1', '=' ,'1');
    }

    public function scopeQueryJoinPublicacao( $query, $campoJoin )
    {
        return $query->join( 'publicacoes', 'publicacoes.id','=' , $campoJoin);
    } 

    public function getPublicacaoColaborador( $busca ){

        $publicacoes = $this->queryPublicacao()
            ->queryJoinPublicacaoFavoritos()
            ->whereExists(function ($query) use ($busca) {
                $query->select(\DB::raw('publicacoes_colaboradores.publicacao_id'))
                      ->from('publicacoes_colaboradores')
                      ->join('funcionarios', 'funcionarios.id', '=', 'publicacoes_colaboradores.funcionario_id')
                      ->whereRaw('publicacoes_colaboradores.publicacao_id = publicacoes.id')
                      ->whereRaw('sem_acento(funcionarios.nome) like sem_acento( \''.$busca.'%\')')
                      ->whereRaw('publicacoes_colaboradores.rascunho = FALSE');
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

            $publicacoes->data = $this->formataData($publicacoes);
        
            return $publicacoes;
    }

    /**
     * [getNaoPublicadas - são as publicações com data de publicação vazias ou maiores que a data atual]
     * @param  [integer]    $id        [id da categoria na publicacao]
     * @return [Collection] Collection [retorna a collection de Publicacao]
     */
    public function getNaoPublicadas( $id ){        
        
        return $this->formataData(Publicacao::
            where('publicacao_categoria_id', '=', $id)
           ->where(function( $query ){
                $query->whereDate( 'dt_publicacao', '>', date('d/m/Y') )
                ->orWhereNull( 'dt_publicacao' );
            })
             ->where(function( $query ){
                $query->whereDate('dt_desativacao', '>=', date('d/m/Y'))
                ->orWhereNull( 'dt_desativacao' );
                                
            })            
            ->selectRaw( $this->selectBusca( false ) )
            ->orderBy('dt_publicacao')
            ->get());
    }

    /**
     * [getDesativadas - são as publicações com data de desativação menor ou igual a data atual ]
     * @param  [integer]    $id        [id da categoria na publicacao]
     * @return [Collection] Collection [retorna a collection da Publicacao]
     */
    public function getDesativadas( $id )
    {
        return $this->formataData(Publicacao::
             where('publicacao_categoria_id', '=', $id)
            ->whereDate( 'dt_desativacao', '<=', date('d/m/Y') )            
            ->selectRaw( $this->selectBusca( false ) )
            ->orderBy('dt_desativacao', 'desc')            
            ->get());
    }

    public function scopeQueryWherePublicadaCargo( $query , $filtroCargo = true)
    {
        if( !$this->getModoEdicaoAtivos() && $filtroCargo == true)
        {
            $pubCargo = new PublicacaoCargo();
            return $query->whereNotIn( 'publicacoes.id', $pubCargo->getIdPublicacoesBloqueadas() );
        }
    }

    public function getModoEdicaoAtivos ()
    {
        $editarPublicacao = \Auth::user()->can( 'BASE_PUBLICACOES_EDITAR' );
        if( $editarPublicacao ){
            return ( !isset( $_COOKIE['editar_publicacao'] ) || $_COOKIE['editar_publicacao'] == 'false' ) ? false : true;
        }
        return false;
    }

    public function getPublicacaoTags( $busca )
    {
        $publicacoes = $this->queryPublicacao()
            ->queryJoinPublicacaoFavoritos()
            ->whereExists(function ($query) use ($busca) {
            $query->select(\DB::raw('publicacoes_tags.publicacao_id'))
                  ->from('publicacoes_tags')
                  ->whereRaw('publicacoes_tags.publicacao_id = publicacoes.id')
                  ->whereRaw('sem_acento(publicacoes_tags.tag) = sem_acento(\''.$busca.'\')')
                  ->whereRaw('publicacoes_tags.rascunho = FALSE');
            })
            ->paginate(20);
    
            $publicacoes->data = $this->formataData($publicacoes);
        
            return $publicacoes;
    }

    public function getPublicacoesSql( $busca ){

        return  $this->queryPublicacao( true ) 
            ->selectRaw('
                ( 
                    ( 
                        ( 
                            ts_rank ( 
                                        publicacoes_busca.documento, 
                                        to_tsquery( \'portuguese\' , formata_tsquery( \''.$busca.'\', true )::text )
                                    ) * 3 
                        ) 
                        + 
                        ( 
                            ts_rank ( 
                                        publicacoes_busca.documento, 
                                        to_tsquery( \'portuguese\' , formata_tsquery( \''.$busca.'\', false )::text )
                                    ) * 1 
                        )  
                    ) / 4 * 100 
                )::NUMERIC(15,2) as media_ponderada' )
            
            ->queryJoinPublicacaoFavoritos()
          
            ->join('publicacoes_busca', function ($join) use ($busca) {                            
                $join->on('publicacoes_busca.id', '=', 'publicacoes.id')
                ->whereRaw('
                    (
                        ( 
                            publicacoes_busca.documento @@ 
                            to_tsquery( \'portuguese\' , formata_tsquery( \''.$busca.'\', true )::text )
                        ) 
                        OR 
                        ( 
                            publicacoes_busca.documento @@ 
                            to_tsquery( \'portuguese\' , formata_tsquery( \''.$busca.'\', false )::text )
                        ) 
                    )'
                );
            });


    }

    public function getPublicacoesBuscaRelacionadas( $busca, $idNot = '', $limit = '' ){
        return $this->formataData(
             $this->getPublicacoesSql( $busca )
             ->whereRaw( 'NOT publicacoes.id = '.$idNot )
             ->limit( ($limit == '' ? '100' : $limit) )
             ->get());
    }

    public function getPublicacoesBuscaPaginate( $busca ){

        $publicacoes =  $this->getPublicacoesSql( $busca )
             ->orderBy('id', 'desc')
             ->paginate(20);

            $publicacoes->data = $this->formataData($publicacoes);
        
            return $publicacoes;

    }


    public function formataProximasPublicacoes( $publicacoes, $request )
    {
        $data = new Date();

        $publicacoes = $publicacoes->map(function ($item) use ($data, $request)
            {
                if( empty($item->dt_publicacao) )
                {
                    $item->exibicao = '';
                    $item->data = 'Não publicado';
                }
                else if( isset($item->dt_publicacao) && empty($item->dt_ultima_atualizacao) )
                {
                    $item->exibicao = $item->dt_publicacao;
                    $item->data = 'Publicação nova';
                }
                else
                {                    
                    if( $data->menorOuIgualAgora($item->dt_publicacao) && $data->maiorAgora($item->dt_ultima_atualizacao) )
                    {
                        if( $request == 'novas' )
                        {
                            $item->exibicao = $item->dt_publicacao;
                            $item->data = 'Publicação nova';
                        }
                        else
                        {
                            $item->exibicao = $item->dt_ultima_atualizacao;
                            $item->data = 'Atualização';
                        }

                    }
                    else if( $data->menorOuIgualAgora($item->dt_publicacao) && $data->menorOuIgualAgora($item->dt_ultima_atualizacao) )
                    {
                        $item->exibicao = $item->dt_ultima_atualizacao;
                        $item->data = 'Atualização';
                    }
                    else if( $data->maiorAgora($item->dt_publicacao) && $data->menorOuIgualAgora($item->dt_ultima_atualizacao) )
                    {
                        $item->exibicao = $item->dt_publicacao;
                        $item->data = 'Publicação nova';
                    }
                    else if( $data->maiorAgora($item->dt_publicacao) && $data->maiorAgora($item->dt_ultima_atualizacao) )
                    {
                        $item->exibicao = $item->dt_publicacao;
                        $item->data = 'Publicação nova';
                    }
                }

                return $item;
            });

        if( $request == 'novas' )
        {
            return $publicacoes->sortBy('id')->groupBy('exibicao')->sortByDesc( function ($publicacao, $key)
            {
                if(isset($key) && $key != '')
                {
                    return Carbon::createFromFormat('d/m/Y', $key);
                }
            });
        }

        return $publicacoes->sortBy('id')->groupBy('exibicao')->sortBy( function ($publicacao, $key)
        {
            if(isset($key) && $key != '')
            {
                return Carbon::createFromFormat('d/m/Y', $key);
            }
        });

    }

    /**
     * Retorna permissões relacionadas a gerenciar categoria das publicações do usuário.
     *
     * @return Array
     */
    public function getPermissaoCategoria()
    {
        return [
                 'categoria_cadastrar' => \Auth::user()->can( 'BASE_PUBLICACOES_CATEGORIA_CADASTRAR' ),
                 'categoria_editar' => \Auth::user()->can( 'BASE_PUBLICACOES_CATEGORIA_EDITAR' ),
                 'categoria_ordenar' => \Auth::user()->can( 'BASE_PUBLICACOES_CATEGORIA_ORDENAR' )
                ];

    }
}
