<?php

namespace App\Exceptions;

use Exception;

class ParametroException extends Exception
{
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}