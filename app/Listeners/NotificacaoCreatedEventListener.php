<?php

namespace App\Listeners;

use App\Events\NotificacaoEvent;
use App\Models\Core\Notificacao;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacaoCreatedEventListener
{
    /**
     * Handle the event.
     *
     * @param  Notificacao  $event
     * @return void
     */
    public function handle( $event )
    {
        $attributes = $event->getAttributesNotification();

        if( $attributes != false )
        {
            /** 
             * Gera uma notificação para cada usuário
             */
            foreach ($attributes['usuario'] as $value)
            {
                $notificacao = new Notificacao;

                if( is_array($value) )
                {
                    $notificacao->usuario_id = $value['id'];
                }
                else
                {
                    $notificacao->usuario_id = $value;
                }

                $this->verificaQtdRegistros( $notificacao->usuario_id );

                /*
                 * Campos obrigatórios para notificação
                 */
                $notificacao->usuario_inclusao_id = \Auth::user()->id;  
                $notificacao->titulo = $attributes['titulo']; 
                $notificacao->mensagem = $attributes['mensagem'];
                $notificacao->modulo = $attributes['modulo']; 
                $notificacao->url = $this->montaUrl( $attributes );

                /*
                 * Campos não obrigatórios para notificação
                 */
                $this->camposNaoObrigatorios( $notificacao, $attributes );

                $notificacao->save();
            }
        }
    }

    private function verificaQtdRegistros( $id )
    {
        $count = Notificacao::where('usuario_id', '=', $id)->count();

        if( $count >= 50 )
        {
            Notificacao::where('usuario_id', '=', $id)->orderBy('created_at')->limit(1)->first()->delete();
        }
    }

    private function camposNaoObrigatorios( $notificacao, $attributes )
    {
        if( array_key_exists('icone', $attributes) )
        {
            $notificacao->icone = $attributes['icone'];
        }

        if( array_key_exists('cor', $attributes) )
        {
            $notificacao->cor = $attributes['cor'];
        }

        if( array_key_exists('created_at', $attributes) )
        {
            $notificacao->created_at = $attributes['data'];
        }
    }
    
    /**
     * [montaUrl description]
     * @param  [array] $attributes  [$attributes['id'] é o id da Model, no caso de tickets é usado para montar o link]
     * @return [string]             [Url formatada]
     */
    private function montaUrl( $attributes )
    {
        if( array_key_exists('url', $attributes) )
        {
            return $attributes['url'];
        }

        if( $attributes['modulo'] == 'ticket' )
        {
            return '/ticket/'.$attributes['id'];
        }
        else if( $attributes['modulo'] == 'meus_tickets' )
        {
            return '/ticket/proprio/'.$attributes['id'];
        }

        return '/';
    }
}
