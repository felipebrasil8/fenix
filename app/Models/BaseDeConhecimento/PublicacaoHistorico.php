<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class PublicacaoHistorico extends Model
{
    use SoftDeletes;
    protected $table = 'publicacoes_historicos';
    protected $fillable = array(
        'movimentacao',
        'alteracao',
        'mensagem',
        'icone',
        'publicacao_id',
        'usuario_id',
        'cor'      
    );
    protected $dates = ['deleted_at'];

    public function setHistoricoDataPublicacao( $old, $new, $id, $observacao ){
      
        $historico = array();
        $historico['id'] = $id;
        $observacao =  str_replace(array("\n\r", "\n", "\r"),' ', strtoupper($observacao)); 
        
        if( $old->dt_publicacao != $new->dt_publicacao ){
            
            // Publicação: 
            $historico['alteracao'] = 'PUBLICACAO';
            $historico['cor'] = '#5CB85C'; 
            $historico['icone'] =  'share-alt'; 
            $historico['mensagem'] = 'alterou data de publicação de "'.$old->dt_publicacao.'" para "'.$new->dt_publicacao.'"';
            $historico['movimentacao'] = '{"Alteração":"data de publicação","antigo":"'.$old->dt_publicacao.'","novo":"'.$new->dt_publicacao.'", "Observação":"'.$observacao.'"}';            

            $this->insertHistorico( $historico );
        }
        if( $old->dt_ultima_atualizacao != $new->dt_ultima_atualizacao ){
            // Atualização: 
            $historico['alteracao'] = 'ATUALIZACAO';
            $historico['cor']  = '#428bca'; 
            $historico['icone'] =  'pencil'; 
            $historico['mensagem'] = 'alterou data da última atualização de "'.$old->dt_ultima_atualizacao.'" para "'.$new->dt_ultima_atualizacao.'"';
            $historico['movimentacao'] = '{"Alteração":"data da última atualização","antigo":"'.$old->dt_ultima_atualizacao.'","novo":"'.$new->dt_ultima_atualizacao.'", "Observação":"'.$observacao.'"}';
            
            $this->insertHistorico( $historico );
        }
        if( $old->dt_desativacao != $new->dt_desativacao ){
            // Desativação: 
            $historico['alteracao'] = 'DESATIVACAO';
            $historico['cor'] = '#dd4b39'; 
            $historico['icone'] =  'ban'; 
            $historico['mensagem'] = 'alterou data de desativação de "'.$old->dt_desativacao.'" para "'.$new->dt_desativacao.'"';
           $historico['movimentacao'] = '{"Alteração":"data de desativação","antigo":"'.$old->dt_desativacao.'","novo":"'.$new->dt_desativacao.'", "Observação":"'.$observacao.'"}';
            
            $this->insertHistorico( $historico );
        }
        if( $old->dt_revisao != $new->dt_revisao ){
            
            // Revisão: 
            $historico['alteracao'] = 'REVISAO';
            $historico['cor'] = '#8a8a8a'; 
            $historico['icone'] =  'file-text-o'; 
            $historico['mensagem'] = 'alterou data de revisão de "'.$old->dt_revisao.'" para "'.$new->dt_revisao.'"';
            $historico['movimentacao'] = '{"Alteração":"data de revisão","antigo":"'.$old->dt_revisao.'","novo":"'.$new->dt_revisao.'", "Observação":"'.$observacao.'"}';
            
            $this->insertHistorico( $historico );
        }


    }
  
    public function setHistoricoCricacaoPublicacao( $id  ){

        $historico = array();
        $historico['id'] = $id;
        $historico['alteracao'] = 'CRIACAO';
        $historico['cor'] = '#ddd'; 
        $historico['icone'] =  'plus'; 
        $historico['mensagem'] = 'criou a publicação';
        $historico['movimentacao'] = null;
        
        $this->insertHistorico( $historico );
    }

 
    private function insertHistorico( $historico ){
        PublicacaoHistorico::insert(
             [
                'alteracao' => $historico['alteracao'],
                'mensagem' => $historico['mensagem'],
                'icone' => $historico['icone'],
                'cor'  => $historico['cor'],
                'usuario_id' => \Auth::user()->id,
                'publicacao_id' => $historico['id'],
                'movimentacao' => $historico['movimentacao']
             ]
        );        
    }

    /**
     * '[getPublicacaoHistorico retorna os historico da publicacao]'
     * @param  [integer] $publicacao_id         [recebe o id da publicacao]
     * @return [Collection] [retorna uma collection de PublicacaoHistorico]
     */
    public function getPublicacaoHistorico( $publicacao_id ){

        return PublicacaoHistorico::where('publicacoes_historicos.publicacao_id', '=', $publicacao_id)
            ->join('publicacoes', 'publicacoes.id', '=', 'publicacoes_historicos.publicacao_id')
            ->join('usuarios', 'usuarios.id', '=', 'publicacoes_historicos.usuario_id')            
            ->select('publicacoes_historicos.id', 'publicacoes_historicos.created_at', 'publicacoes_historicos.mensagem', 'publicacoes_historicos.icone', 'publicacoes_historicos.cor', 'usuarios.nome', 'publicacoes_historicos.alteracao')
            ->selectRaw("movimentacao->>'Observação' as observacao")
            ->selectRaw('publicacoes_historicos.created_at as horario')            
            ->orderBy('publicacoes_historicos.created_at', 'desc')
            ->get();
    }

    public function getCreatedAtAttribute( $value ){        
        if(!is_null($value)){
            return Carbon::parse($value)->format('d/m/Y');
        }

        return null;
    }

    public function getHorarioAttribute( $value ){

        if(!is_null($value)){            
            return Carbon::parse($value)->format('H:i');
        }

        return null;
    }

    public function setHistoricoPublicacaoRascunho( $id, $tags, $colaboradores, $conteudos )
    {
        if( $tags > 0 || $colaboradores > 0 || $conteudos > 0 )
        {
            $historico = array();
            $historico['id'] = $id;
            $historico['alteracao'] = 'RASCUNHO_NOVO';
            $historico['cor'] = '#5bc0de'; 
            $historico['icone'] =  'files-o'; 
            $historico['mensagem'] = 'criou um novo rascunho da publicação';
            $historico['movimentacao'] = '{"Quantidade de Tags":"'.$tags.'","Quantidade de Colaboradores":"'.$colaboradores.'","Quantidade de Conteúdos":"'.$conteudos.'"}';
            
            $this->insertHistorico( $historico );
        }
    }

    public function setHistoricoPublicacaoConfirmRascunho( $id, $rascunho, $publicacao )
    {
        $historico = array();
        $historico['id'] = $id;
        $historico['alteracao'] = 'RASCUNHO_FINALIZADO';
        $historico['cor'] = '#5bc0de'; 
        $historico['icone'] =  'exchange'; 
        $historico['mensagem'] = 'converteu o rascunho em publicação';
        $historico['movimentacao'] = '{"Quantidade de Tags no rascunho":"'.$rascunho['tags'].'","Quantidade de Colaboradores no rascunho":"'.$rascunho['colaboradores'].'","Quantidade de Conteúdos no rascunho":"'.$rascunho['conteudos'].'","Quantidade de Tags na publicação":"'.$publicacao['tags'].'","Quantidade de Colaboradores na publicação":"'.$publicacao['colaboradores'].'","Quantidade de Conteúdos na publicação":"'.$publicacao['conteudos'].'"}';

        $this->insertHistorico( $historico );
    }

    public function setHistoricoPublicacaoDeleteRascunho( $id, $rascunho )
    {
        if( $rascunho['tags'] > 0 || $rascunho['colaboradores'] > 0 || $rascunho['conteudos'] > 0 )
        {
            $historico = array();
            $historico['id'] = $id;
            $historico['alteracao'] = 'RASCUNHO_EXCLUIDO';
            $historico['cor'] = '#F39C12'; 
            $historico['icone'] =  'trash-o'; 
            $historico['mensagem'] = 'excluiu o rascunho da publicação';
            $historico['movimentacao'] = '{"Quantidade de Tags":"'.$rascunho['tags'].'","Quantidade de Colaboradores":"'.$rascunho['colaboradores'].'","Quantidade de Conteúdos":"'.$rascunho['conteudos'].'"}';

            $this->insertHistorico( $historico );
        }
    }

}
