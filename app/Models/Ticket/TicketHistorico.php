<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket\TicketStatus;
use App\Models\Ticket\TicketPrioridade;
use App\Models\Ticket\TicketCategoria;
use App\Models\Configuracao\Usuario;

class TicketHistorico extends Model
{
    protected $table = 'tickets_historico';
    protected $fillable = array(
		'usuario_id',
		'notificacao_id',
        'ticket_id',
        'movimentacao',
        'mensagem',
        'icone',
        'cor',
        'interacao',
        'interno',
	);
    public    $timestamps = false;

    public function historicoTicketCreate( $usuario_id, $ticket_id )
    {
        $historico = TicketHistorico::insertGetId(
            [ 
                'usuario_id' =>  $usuario_id, 
                'ticket_id' => $ticket_id,
                'mensagem' => 'abriu o ticket',
                'icone' => 'plus',
                'cor' => '#ddd',
                'interno' => 'false',
                'interacao' => 'false',
            ] 
        );
    }

    public function historicoImagemTicketCreate( $usuario_id, $ticket_id )
    {
        $historico = TicketHistorico::insertGetId(
            [ 
                'usuario_id' =>  $usuario_id, 
                'ticket_id' => $ticket_id,
                'mensagem' => 'inseriu  uma nova imagem',
                'icone' => 'image',
                'cor' => '#605ca8',
                'interno' => 'false',
                'interacao' => 'false',
            ] 
        );
    }

    public function historicoImagemTicketDelete( $usuario_id, $ticket_id ){

        $historico = TicketHistorico::insertGetId(
            [ 
                'usuario_id' =>  intval($usuario_id), 
                'ticket_id' => intval($ticket_id),
                'mensagem' => 'excluiu uma imagem',
                'icone' => 'image',
                'cor' => '#605ca8',
                'interno' => 'false',
                'interacao' => 'false',
            ] 
        );

    }


    public function historicoTicketUpadate( $old, $new, $usuario_id,  $ticket_id )
    {

            
        if( !is_null( $old['usuario_responsavel_id']) )
        {   
           
            if($old['usuario_responsavel_id'] != $new['usuario_responsavel_id']){

                $old_info = Usuario::where('id', '=', $old['usuario_responsavel_id'])
                                            ->select('nome')
                                            ->first();

                $new_info = Usuario::where('id', '=', $new['usuario_responsavel_id'])
                                            ->select('nome')
                                            ->first();

                if( is_null($new_info) )
                {
                    $new_info_nome = '';
                }
                else
                {
                    $new_info_nome = $new_info->nome;
                }

                $mensagem = 'alterou responsável de "'.$old_info->nome.'" para "'.$new_info_nome.'"';
                $movimentacao = '{"Alteração":"responsavel", "antigo":"'.$old_info->nome.'", "novo":"'.$new_info_nome.'" }';
                   $historico = TicketHistorico::insertGetId(
                [ 
                    'usuario_id' =>  $usuario_id, 
                    'ticket_id' => $ticket_id,
                    'mensagem' => $mensagem,
                    'movimentacao' => $movimentacao,
                    'icone' => 'user',
                    'cor' => '#5bc0de',
                    'interno' => 'false',
                    'interacao' => 'false',
                ] );
            }
        
        } else {
            
            $new_info = Usuario::where('id', '=', $new['usuario_responsavel_id'])
                                            ->select('nome')
                                            ->first();

            if( is_null($new_info) )
            {
                $new_info_nome = '';
            }
            else
            {
                $new_info_nome = $new_info->nome;
            }

            $mensagem = 'alterou responsável para "'.$new_info_nome.'"';
            $movimentacao = '{"Alteração":"usuario_responsavel_id", "antigo":"", "novo":"'.$new['usuario_responsavel_id'].'" }';
            $historico = TicketHistorico::insertGetId(
                [ 
                    'usuario_id' =>  $usuario_id, 
                    'ticket_id' => $ticket_id,
                    'mensagem' => $mensagem,
                    'movimentacao' => $movimentacao,
                    'icone' => 'user',
                    'cor' => '#5bc0de',
                    'interno' => 'false',
                    'interacao' => 'false',
                ] 
            );
        }


        if($old['tickets_status_id'] != $new['tickets_status_id']) {

            $old_info = TicketStatus::where('id', '=', $old['tickets_status_id'])
                                        ->select('nome')
                                        ->first();

            $new_info = TicketStatus::where('id', '=', $new['tickets_status_id'])
                                        ->select('nome')
                                        ->first();
            
            $mensagem = 'alterou status de "'.$old_info->nome.'" para "'.$new_info->nome.'"';
            $movimentacao = '{"Alteração":"tickets_status_id", "antigo":"'.$old['tickets_status_id'].'", "novo":"'.$new['tickets_status_id'].'" }';
            $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
   
        }


        if($old['usuario_solicitante_id'] != $new['usuario_solicitante_id']) {
            $old_info = Usuario::where('id', '=', $old['usuario_solicitante_id'])
                                        ->select('nome')
                                        ->first();

            $new_info = Usuario::where('id', '=', $new['usuario_solicitante_id'])
                                        ->select('nome')
                                        ->first();

            $mensagem = 'alterou solicitante de "'.$old_info->nome.'" para "'.$new_info->nome.'"';
            $movimentacao = '{"Alteração":"usuario_solicitante_id", "antigo":"'.$old['usuario_solicitante_id'].'", "novo":"'.$new['usuario_solicitante_id'].'" }';
            $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
        }

        
        if($old['tickets_prioridade_id'] != $new['tickets_prioridade_id']) {
            $old_info = TicketPrioridade::where('id', '=', $old['tickets_prioridade_id'])
                                        ->select('nome')
                                        ->first();

            $new_info = TicketPrioridade::where('id', '=', $new['tickets_prioridade_id'])
                                        ->select('nome')
                                        ->first();
            $mensagem = 'alterou prioridade de "'.$old_info->nome.'" para "'.$new_info->nome.'"';
            $movimentacao = '{"Alteração":"tickets_prioridade_id", "antigo":"'.$old['tickets_prioridade_id'].'", "novo":"'.$new['tickets_prioridade_id'].'" }';
            $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
        }
        
        if($old['assunto'] != $new['assunto']) {
           
            $mensagem = 'alterou assunto de "'.$old['assunto'].'" para "'.$new['assunto'].'"';
            $movimentacao = '{"Alteração":"assunto", "antigo":"'.$old['assunto'].'", "novo":"'.$new['assunto'].'" }';
            $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
        }
        
        if($old['tickets_categoria_id'] != $new['tickets_categoria_id']) {
            $old_info = TicketCategoria::where('id', '=', $old['tickets_categoria_id'])
                                        ->select('nome')
                                        ->first();

            $new_info = TicketCategoria::where('id', '=', $new['tickets_categoria_id'])
                                        ->select('nome')
                                        ->first();
            $mensagem = 'alterou categoria de "'.$old_info->nome.'" para "'.$new_info->nome.'"';
            $movimentacao = '{"Alteração":"tickets_categoria_id", "antigo":"'.$old['tickets_categoria_id'].'", "novo":"'.$new['tickets_categoria_id'].'" }';
            $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
        }


        if( !is_null($old['dt_previsao']) )
        {   
            if(date_format(date_create($old['dt_previsao']) , 'd/m/Y') != date_format(date_create($new['dt_previsao']) , 'd/m/Y')) {
                
                $new_data = date_format(date_create($new['dt_previsao']) , 'd/m/Y');
                if( is_null($new['dt_previsao']) )
                {
                    $new_data = '';
                }

                $mensagem = 'alterou Data de previsão de "'.date_format(date_create($old['dt_previsao']) , 'd/m/Y').'" para "'.$new_data.'"';
                $movimentacao = '{"Alteração":"dt_previsao", "antigo":"'.date_format(date_create($old['dt_previsao']) , 'd/m/Y').'", "novo":"'.$new_data.'" }';
                $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
            }
        } 
        else
        {
            if(date_format(date_create($old['dt_previsao']) , 'd/m/Y') != date_format(date_create($new['dt_previsao']) , 'd/m/Y'))
            {    
                $mensagem = 'alterou Data de previsão para "'.date_format(date_create($new['dt_previsao']) , 'd/m/Y').'"';
                $movimentacao = '{"Alteração":"dt_previsao", "antigo":"", "novo":"'.date_format(date_create($new['dt_previsao']) , 'd/m/Y').'" }';
                    $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
            }
        }

        if( !is_null($old['dt_notificacao']) )
        {   
            if(date_format(date_create($old['dt_notificacao']) , 'd/m/Y') != date_format(date_create($new['dt_notificacao']) , 'd/m/Y')) {
                
                $new_data = date_format(date_create($new['dt_notificacao']) , 'd/m/Y');
                if( is_null($new['dt_notificacao']) )
                {
                    $new_data = '';
                }
                
                $mensagem = 'alterou Data de notificação de "'.date_format(date_create($old['dt_notificacao']) , 'd/m/Y').'" para "'.$new_data.'"';
                $movimentacao = '{"Alteração":"dt_notificacao", "antigo":"'.date_format(date_create($old['dt_notificacao']) , 'd/m/Y').'", "novo":"'.$new_data.'" }';
                $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
            }
        } 
        else
        {
            if(date_format(date_create($old['dt_notificacao']) , 'd/m/Y') != date_format(date_create($new['dt_notificacao']) , 'd/m/Y'))
            {    
                $mensagem = 'alterou Data de notificação para "'.date_format(date_create($new['dt_notificacao']) , 'd/m/Y').'"';
                $movimentacao = '{"Alteração":"dt_notificacao", "antigo":"", "novo":"'.date_format(date_create($new['dt_notificacao']) , 'd/m/Y').'" }';
                    $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao);
            }
        }

        if( !is_null($old['avaliacao']) )
        {   
            if($old['avaliacao'] != $new['avaliacao'])
            {    
                $mensagem = 'avaliou o ticket';
                $movimentacao = '{"Alteração":"avaliacao", "antigo":"'.$old['avaliacao'].'", "novo":"'.$new['avaliacao'].'" }';
                $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao, 'star', '#f5d401');
            }
        } 
        else
        {
            if($old['avaliacao'] != $new['avaliacao'])
            {    
                $mensagem = 'avaliou o ticket';
                $movimentacao = '{"Alteração":"avaliacao", "antigo":"", "novo":"'.$new['avaliacao'].'" }';
                $this->inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao, 'star', '#f5d401');
            }
        }
    }


    public function tickets(){
    	return $this->belongsTo('App\Models\Ticket\Ticket');
    }

    public function usuarios(){
        return $this->belongsTo('App\Models\Configuracao\Usuario', 'usuario_id');   
    }


   
    public function inserirHistorio($mensagem, $usuario_id, $ticket_id, $movimentacao, $icone='pencil', $cor='#428bca'){

        $historico = TicketHistorico::insertGetId(
            [ 
                'usuario_id' =>  $usuario_id, 
                'ticket_id' => $ticket_id,
                'mensagem' => $mensagem,
                'movimentacao' => $movimentacao,
                'icone' => $icone,
                'cor' => $cor,
                'interno' => 'false',
                'interacao' => 'false',
            ] 
        );


    }


    



}
