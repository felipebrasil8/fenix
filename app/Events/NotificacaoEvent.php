<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Core\Notificacao;
use App\Models\Configuracao\Usuario;

class NotificacaoEvent
{
    private $notificacao;

    protected $attributesNotification;

    public function __construct( $model, $attributes )
    {
        $this->notificacao = $model;

        $this->attributesNotification = $attributes;
    }

    public function getModel()
    {
        return $this->notificacao;
    }

    public function getAttributesNotification()
    {
    	if( array_key_exists('notificacao', $this->attributesNotification) )
        	return $this->attributesNotification['notificacao'];
        else
        	return false;
    }
}
