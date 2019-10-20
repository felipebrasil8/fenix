<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;

use App\Models\Ticket\Ticket;
use App\Models\Configuracao\Usuario;

use App\Http\Controllers\Controller;

class BuscaMenuController extends Controller
{

    public function search( Request $request )
    {
        $busca = $this->formatString()->strToUpperCustom($request->busca);
        $ticket = strripos($busca, '#TK');

        if( $ticket === 0 )
        {
            $busca = trim(str_replace('#' , '', $busca));
            return $this->getBuscaMenuTickets( $busca );
        }

        return [];
    }

    private function getBuscaMenuTickets( $busca )
    {
        $usuario = new Usuario();
        $usuario_departamento = $usuario->getDepartamento( \Auth::user()->id )->departamento_id;
        
        $todos = \Auth::user()->can( 'TICKETS_VISUALIZAR_TODOS' );
        $filter_permissao = \Auth::user()->can( 'TICKETS_VISUALIZAR_DEPARTAMENTO' );

        return Ticket::getBuscaMenuTickets( $busca, $todos, $usuario_departamento, $filter_permissao );
    }
}
