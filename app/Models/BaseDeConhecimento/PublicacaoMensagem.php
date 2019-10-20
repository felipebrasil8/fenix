<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PublicacaoMensagem extends Model
{
    protected $table = 'publicacoes_mensagens';

    protected $fillable = array(
        'id',
        'created_at',
        'usuario_inclusao_id',
        'publicacao_id',
        'mensagem',
        'respondida',
        'resposta',
        'dt_resposta',
        'usuario_resposta_id'
    );
    
      /**
      * The name of the "updated at" column.
      *
      * @var string
      */
     const UPDATED_AT = null;

     public function setUpdatedAt($value)
     {
         // Do nothing.
     }



    public function getCreatedAtAttribute( $value ) {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i:s');        
        }
        return $value;
    }

    public function getDtRespostaAttribute( $value ) {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y H:i:s');        
        }
        return $value;
    }

    public function getRespostaAttribute( $value ) {
        if( !is_null($value)){            
            return nl2br($value);        
        }
        return $value;
    }



    public function insertPublicacaoMensagem( $dadosMensagem )
    {
        if( isset($dadosMensagem->mensagem) && isset($dadosMensagem->publicacao_id) )
        {
            $id = PublicacaoMensagem::insertGetId([
                        'usuario_inclusao_id' => \Auth::user()->id,
                        'publicacao_id' => $dadosMensagem->publicacao_id,
                        'mensagem' => trim($dadosMensagem->mensagem),
                    ]);

            return $id;
        }

        return null;
    }

        public function getMensagem( $id )
    {
        return PublicacaoMensagem::
            select( 'publicacoes_mensagens.id AS publicacoes_mensagens_id', 
                    'publicacoes_mensagens.mensagem',
                    'publicacoes_mensagens.created_at', 
                    'publicacoes_mensagens.created_at AS data_interacao',
                    'publicacoes_mensagens.respondida',
                    'publicacoes_mensagens.resposta',
                    'publicacoes_mensagens.dt_resposta',
                    'tb.nome AS respondeu_nome',
                    'publicacoes.id AS publicacoes_id',
                    'publicacoes.titulo',
                    'funcionarios.id AS funcionario_id',
                    'usuarios.nome AS usuario_nome' )
            ->join( 'publicacoes', 'publicacoes.id', '=', 'publicacoes_mensagens.publicacao_id')
            ->join( 'usuarios', 'usuarios.id', '=', 'publicacoes_mensagens.usuario_inclusao_id')
            ->join( 'funcionarios', 'funcionarios.id', '=', 'usuarios.funcionario_id')
            ->leftJoin( 'usuarios AS tb', 'tb.id', '=', 'publicacoes_mensagens.usuario_resposta_id')
            ->where( 'publicacoes_mensagens.id', '=', $id)
            ->get();
    }

    public function getMensagens( $filtros )
    {
        return $this->queryMensagens()
            ->whereRaw("date(publicacoes_mensagens.created_at) >= date('".Carbon::createFromFormat('d/m/Y', $filtros->data_de)->format('Y-m-d')."')")
            ->whereRaw("date(publicacoes_mensagens.created_at) <= date('".Carbon::createFromFormat('d/m/Y', $filtros->data_ate)->format('Y-m-d')."')")
            ->filtroFuncionario( $filtros )
            ->filtroPublicacao( $filtros )
            ->filtroMensagem( $filtros )
            ->filtroRespondidas( $filtros )
            ->orderByRaw('publicacoes_mensagens.created_at desc')
            ->get();
    }

    public function queryMensagens(){
        return PublicacaoMensagem::
            select( 'publicacoes_mensagens.id AS publicacoes_mensagens_id', 
                    'publicacoes_mensagens.mensagem',
                    'publicacoes_mensagens.created_at', 
                    'publicacoes_mensagens.created_at AS data_interacao',
                    'publicacoes_mensagens.respondida',
                    'publicacoes_mensagens.resposta',
                    'publicacoes_mensagens.dt_resposta',
                    'publicacoes.id AS publicacoes_id',
                    'tb.nome AS respondeu_nome',
                    'publicacoes.titulo',
                    'funcionarios.id AS funcionario_id',
                    'usuarios.nome AS usuario_nome' )
            ->join( 'publicacoes', 'publicacoes.id', '=', 'publicacoes_mensagens.publicacao_id')
            ->join( 'usuarios', 'usuarios.id', '=', 'publicacoes_mensagens.usuario_inclusao_id')
            ->join( 'funcionarios', 'funcionarios.id', '=', 'usuarios.funcionario_id')
            ->leftJoin( 'usuarios AS tb', 'tb.id', '=', 'publicacoes_mensagens.usuario_resposta_id');
    }


    public function scopeFiltroFuncionario($query, $filtros)
    {
        if( isset($filtros->funcionarios) && $filtros->funcionarios != '' )
        {
            return $query->where( 'funcionarios.id', '=', $filtros->funcionarios);
        }
    }

    public function scopeFiltroRespondidas($query, $filtros)
    {
        if( isset($filtros->respondida) && $filtros->respondida != 'ambos' )
        {
            return $query->where( 'publicacoes_mensagens.respondida', '=', $filtros->respondida);
        }
    }

    public function scopeFiltroPublicacao($query, $filtros)
    {
        if( isset($filtros->publicacao) && $filtros->publicacao != '' )
        {
            return $query->whereRaw('upper(sem_acento(publicacoes.titulo)) LIKE upper(sem_acento(\'%'.$filtros->publicacao.'%\'))');
        }
    }

    public function scopeFiltroMensagem($query, $filtros)
    {
        if( isset($filtros->mensagem) && $filtros->mensagem != '' )
        {
            return $query->whereRaw('upper(sem_acento(publicacoes_mensagens.mensagem)) LIKE upper(sem_acento(\'%'.$filtros->mensagem.'%\'))');
        }
    }




}
