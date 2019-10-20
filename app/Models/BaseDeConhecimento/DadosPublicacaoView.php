<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Util\Date;

class DadosPublicacaoView extends Model
{
    protected $table = 'dados_publicacao_view';

    public function getDadosPublicacaoView(){
        return DadosPublicacaoView::all();
    }

    //MUTATORS
    
    //categoria
    public function getCategoriaAttribute($value){
        if(!is_null($value)){
            return $value;
        }

        return '-';
    }

    //categoria_pai
    public function getCategoriaPaiAttribute($value){
        if(!is_null($value)){
            return $value;
        }

        return '-';     
    }

    //titulo
    public function getTituloAttribute($value){
        if(!is_null($value)){
            return $value;
        }

        return '-';     
    }

    //resumo
    public function getResumoAttribute($value){
        if(!is_null($value)){
            return $value;
        }

        return '-';     
    }
    
    //data_publicacao
    public function getDataPublicacaoAttribute($value){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataDataExcel($value);
        }

        return '-';
    }

    //data_ultima_atualizacao
    public function getDataUltimaAtualizacaoAttribute($value){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataDataExcel($value);
        }

        return '-';
    }
    
    //data_revisao
    public function getDataRevisaoAttribute($value){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataDataExcel($value);
        }
        return '-';
    }

    //data_desativacao
    public function getDataDesativacaoAttribute($value){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataDataExcel($value);
        }

        return '-';
    }

    //colaboradores  
    public function getColaboradoresAttribute($value){
        if(!is_null($value)){
            return $value;
        }

        return '-';     
    }

    //tags
    public function getTagsAttribute($value){
        if(!is_null($value)){
            return $value;
        }

        return '-';     
    }

    //datas_atualizacao
    public function getDatasAtualizacaoAttribute($value){

        // dd($value);
        if(!is_null($value)){
                return $value;
        }

        return '-';
    }

    //restricao_acesso
    public function getRestricaoAcessoAttribute($value){
        if(!is_null($value)){
            return $value;
        }

        return 'NAO';       
    }

    //qtde_publicacoes_relacionadas
    public function getQtdePublicacoesRelacionadasAttribute($value){        
        if(!is_null($value)){
            return ''.$value.'';
        }

        return 0;       
    }    
    
    //qtde_favoritos
    public function getQtdeFavoritosAttribute($value){

        if(!is_null($value)){
            return ''.$value.'';
        }

        return 0;
    }    

    //qtde_acessos_total
    public function getQtdeAcessosTotalAttribute($value){

        if(!is_null($value)){
            return ''.$value.'';
        }

        return 0;
    }

    //qtde_acessos_ultimos_trinta_dias
    public function getQtdeAcessosUltimosTrintaDiasAttribute($value){
        
        if(!is_null($value)){
            return ''.$value.'';
        }

        return 0;
    }    

    //qtde_de_usuarios_que_acessou
    public function getQtdeDeUsuariosQueAcessouAttribute($value){
        if(!is_null($value)){
            return ''.$value.'';
        }

        return 0;
    }

    //qtde_de_usuarios_que_acessou_ultimos_trinta_dias
    public function getQtdeDeUsuariosQueAcessouUltimosTrintaDiasAttribute($value){
        
        if(!is_null($value)){
            return ''.$value.'';
        }

        return 0;
    }

    //usuario_inclusao
    public function getUsuarioInclusaoAttribute($value){
        if(!is_null($value)){
            return $value;
        }

        return '-';
    }

    //data_inclusao
    public function getDataInclusaoAttribute($value){
        $data = new Date();        
        if(!is_null($value)){
            return $data->formataDataExcel($value);
        }

        return '-';
    }

    //hora_inclusao
    public function getHoraInclusaoAttribute($value){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataHoraExcel($value);
        }

        return '-';
    }

    //usuario_ultima_alteracao
    public function getUsuarioUltimaAlteracaoAttribute($value){

        if(!is_null($value)){
            return $value;
        }

        return '-';
    }    

    //data_ultima_alteracao
    public function getDataUltimaAlteracaoAttribute($value){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataDataExcel($value);
        }

        return '-';
    }

    //hora_ultima_alteracao
    public function getHoraUltimaAlteracaoAttribute($value){
        $data = new Date();
        if(!is_null($value)){
            return $data->formataHoraExcel($value);
        }

        return '-';
    }

    //qtde_recomendacoes_total
    public function getQtdeRecomendacoesTotalAttribute($value){

        if(!is_null($value)){
            return ''.$value.'';
        }

        return 0;
    }
}
