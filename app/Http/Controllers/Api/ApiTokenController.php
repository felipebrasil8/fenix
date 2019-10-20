<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket\Ticket;
use App\Models\Configuracao\Usuario;


class ApiTokenController extends Controller
{
    public function autorizaUrl(Request $request)
    {
        // Retorna a instancia de GuardToken (Logado pela API)
        $user = \Auth::guard('api')->user();
        if( !is_null($user) )
        {
             // Cria a instancia de SessionGuard
            \Auth::guard('web')->login($user);
            return redirect($request->request->get('redirect').'?api_token='.$request->request->get('api_token'));
        }
        else
        {
            return redirect('login');
        }
    }

    /**
     * Este metodo foi criado para retornar JSON, chamadas por API
     * Ex: http://192.168.9.243:3000/api/ticket/totais/{departamento_id}?api_token={api_token}
     * 
     * @param int $departamento_id
     * @return JSON
     */
    public function dadosTickets($departamento_id)
    {

        try {
            $permissao = $this->authorize( 'getPermissaoTicketVisualizarIndexApi', Ticket::class )->message();
        } catch (\Exception $e) {
            return response()->json([ 'error' => 'Usuário não possui autorização'], 403);
        }
        
        /**
         * Usuário possui pelo menos uma das 3 permissões
         */
        $tickets = new Ticket();
        $usuario = new Usuario();
        $usuario_departamento = $usuario->getDepartamento( \Auth::guard('api')->user()->id );


        if ( !$permissao['todos'] && !$permissao['dashboard'] && ( empty($usuario_departamento) || $departamento_id != $usuario_departamento->departamento_id ) ) {
            return response()->json([ 'error' => 'Usuário não possui autorização para este departamento'], 403);
        }

        $qtdeTicketsAbertos = $tickets->getQtdeTicketsAbertos($departamento_id);

        $qtdeTicketsAgrupadosStatusMesAtual = $tickets->getQtdeTicketsAgrupadosStatusMesAtual($departamento_id);

        $qtdeTicketsAgrupadosResponsavelMesAtual = $tickets->getQtdeTicketsAgrupadosResponsavelMesAtual($departamento_id);

        return response()->json([
            'qtde_tickets_abertos'                     => $qtdeTicketsAbertos,
            'qtdeTicketsAgrupadosMesAtual'             => $qtdeTicketsAgrupadosStatusMesAtual,
            'qtdeTicketsAgrupadosResponsavelMesAtual'  => $qtdeTicketsAgrupadosResponsavelMesAtual,
        ]);
        
    }

    public function show( $id )
    {
        return $this->showTicket( $id, 'getPermissaoTicketVisualizar', 'TICKETS_VISUALIZAR_DEPARTAMENTO', 'show' );
    }
}