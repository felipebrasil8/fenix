<?php

namespace App\Policies;

use App\Models\Core\PermissaoUsuario;
use App\Models\Configuracao\Usuario;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketImagem;
use App\Models\Ticket\TicketStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    private $permissaoUsuario;
    private $usuario;

    private $visualizar_todos;
    private $visualizar_departamento;
    private $visualizar_proprio;

    public function __construct( PermissaoUsuario $permissaoUsuario, Usuario $usuario )
    {
        $this->permissaoUsuario = $permissaoUsuario;
        $this->usuario = $usuario;

        $this->visualizar_todos = false;
        $this->visualizar_departamento = false;
        $this->visualizar_proprio = false;
    }

    /*
     * Retorna a pemissão de criar_outros
     * Caso não tenha nenhuma permissão lança uma Exception
     */
    public function getPermissaoTicketCadastrar( $user )
    {
        $criar_outros = false;
        $criar_proprio = false;

        $criar_outros = $this->permissaoUsuario->permissaoIdentificador( $user, 'TICKETS_CRIAR_OUTROS' );
        $criar_proprio = $this->permissaoUsuario->permissaoIdentificador( $user, 'TICKETS_CRIAR_PROPRIO' );

        if ( $criar_outros || $criar_proprio )
        {
            return $this->allow( $criar_outros );
        }

        return false;
    }

    public function getPermissaoTicketPesquisar( $user, $ticket )
    {
        $this->ticketsVisualizar( $user );

        if( !$this->visualizar_todos )
        {
            if( $this->visualizar_departamento )
            { 
                return $this->allow( ['departamento_id', '=' , $ticket] );
            }
            
            // if( $this->visualizar_proprio == true )
            // { 
            //     return $this->allow( ['usuario_solicitante_id', '=' , $user->id ] );
            // }
        }

        return $this->allow( [] );
    }

    public function getPermissaoTicketPesquisarProprio( $user, $ticket )
    {
        $this->ticketsVisualizar( $user );
      
        if( $this->visualizar_proprio == true )
        { 
            return $this->allow( ['usuario_solicitante_id', '=' , $user->id ] );
        }
        return $this->allow( [] );
    }
    
    public function getPermissaoTicketVisualizar( $user, $ticket )
    {
        $this->ticketsVisualizar( $user );
        $tem_permissao_ticket = false;

        /*
         * Caso tenha permissão de visualizar_departamento
         */
        if( $this->visualizar_todos == false && $this->visualizar_departamento == true )
        {
            if( $this->usuario->getDepartamento( $user->id )->departamento_id == $ticket->departamento_id )
            {
                $tem_permissao_ticket = true;
            }
        }

        return ( $this->visualizar_todos || $tem_permissao_ticket );
    }

    public function getPermissaoTicketVisualizarProprio( $user, $ticket )
    {
        $this->ticketsVisualizar( $user );
        $tem_permissao_ticket = false;

        /*
         * Caso só tenha permissão de visualizar_proprio
         */
        if( $this->visualizar_proprio == true )
        {
            if( $user->id == $ticket->usuario_solicitante_id )
            {
                $tem_permissao_ticket = true;
            }
        }

        return $tem_permissao_ticket;
    }

    public function getPermissaoTicketVisualizarIndex( $user )
    {
        $this->ticketsVisualizar( $user );

        if ( $this->visualizar_todos || $this->visualizar_departamento )
        {
            return $this->allow( $this->visualizar_todos );
        }

        return false;
    }

    public function getPermissaoTicketVisualizarIndexProprio( $user )
    {
        $this->ticketsVisualizar( $user );
        
        return $this->visualizar_proprio;
    }

    public function getPermissaoTicketVisualizarIndexApi( $user )
    {
        $this->ticketsVisualizar( $user );
        $this->visualizar_todos_dashboard = $this->permissaoUsuario->permissaoIdentificador( $user, 'TICKETS_VISUALIZAR_DASHBOARD_TODOS_DEPARTAMENTOS' );

        if ( $this->visualizar_todos || $this->visualizar_departamento || $this->visualizar_todos_dashboard )
        {
            return $this->allow( [
                'todos' => $this->visualizar_todos,
                'departamento' => $this->visualizar_departamento,
                'dashboard' => $this->visualizar_todos_dashboard
            ] );
        }

        return false;
    }

    public function getPermissaoTicketEditar( $user, $ticket )
    {
        return $this->permissaoDepartamento( $user, $ticket, 'TICKETS_TODOS_EDITAR', 'TICKETS_DEPARTAMENTO_EDITAR' );
    }

    public function getPermissaoTicketResponder( $user, $ticket )
    {
        return $this->permissaoDepartamento( $user, $ticket, 'TICKETS_TODOS_RESPONDER', 'TICKETS_DEPARTAMENTO_RESPONDER' );
    }

    public function getPermissaoTicketResponderBool( $user, $ticket )
    {
        return $this->allow( $this->permissaoDepartamento( $user, $ticket, 'TICKETS_TODOS_RESPONDER', 'TICKETS_DEPARTAMENTO_RESPONDER' ) );
    }

    private function permissaoDepartamento( $user, $ticket, $user_todos, $user_departamento )
    {
        $todos = $this->permissaoUsuario->permissaoIdentificador( $user, $user_todos );
        $departamento = $this->permissaoUsuario->permissaoIdentificador( $user, $user_departamento );

        $tem_permissao_ticket = false;

        if( $todos == false && $departamento == true )
        {
            if( $this->usuario->getDepartamento( $user->id )->departamento_id == $ticket->departamento_id )
            {
                $tem_permissao_ticket = true;
            }
        }
        
        return ( $todos || $tem_permissao_ticket );
    }

    private function ticketsVisualizar( $user )
    {
        $this->visualizar_todos = $this->permissaoUsuario->permissaoIdentificador( $user, 'TICKETS_VISUALIZAR_TODOS' );
        $this->visualizar_departamento = $this->permissaoUsuario->permissaoIdentificador( $user, 'TICKETS_VISUALIZAR_DEPARTAMENTO' );
        $this->visualizar_proprio = $this->permissaoUsuario->permissaoIdentificador( $user, 'TICKETS_VISUALIZAR_PROPRIO' );
    }    

    public function getPermissaoSubirImagemTickettFechado( $user, $ticket )
    {
        $cond = Ticket::where('tickets.id', $ticket->id)
        ->select('tickets_status.aberto')
        ->leftJoin('tickets_status', 'tickets.tickets_status_id', 
                '=', 
                'tickets_status.id')->first();
        
        if( $cond->aberto == true){
            return true;        
        }        
        
        return false;        
    }
}