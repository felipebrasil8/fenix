<?php

namespace App\Http\Controllers\Ticket;

use Validator;
use App\Http\Controllers\Controller;
use App\Models\Configuracao\Usuario;
use App\Models\Ticket\TicketImagem;
use App\Models\Ticket\TicketHistorico;
use Illuminate\Support\Str;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Core\ImagemController;

class TicketImagemController extends Controller
{	
    private $str;
	private $imagem;

    public function __construct( Str $str,  ImagemController $imagem, TicketHistorico $ticket_historico) {

        $this->str = $str;
        $this->imagem = $imagem;        
        $this->ticket_historico = $ticket_historico;
    }

    /**
     * @param Object Request
     * @return 
     */
    public function store( Request $request ){

        try{

            $img = new ImagemController;

            $string = new Str;

            $validator = Validator::make($request->all(), [
                'imagem' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',                
                'ticket_id' => 'required|numeric',
            ]);

            if ($validator->fails()){

                return \Response::json(['errors' => [ $validator->errors()->all() ]], 404);
            }

            $file = $request->file('imagem');

            $base64 = $img->geraImagemBase64( $file, 1280, 'jpeg' );

            $base64_miniatura = $img->geraImagemThumbBase64( $file, 'jpeg' );

            /*
             *  Salva Historico
             */    
            $this->ticket_historico->historicoImagemTicketCreate( \Auth()->user()->id, $request->ticket_id );

            $ticket = TicketImagem::create( 
                [ 
                    'usuario_inclusao_id' => \Auth::user()->id,
                    'ticket_id'           => $request->ticket_id,
                    'texto'               => $string->upper( $request->texto ),
                    'imagem'              => $base64,
                    'imagem_miniatura'    => $base64_miniatura
                ] 
            );
            

        }catch( \Exception $e ){

            return \Response::json(['errors' => [ $e->getMessage() ]], 404);
        }        
        
        return ['status'=>true, 'mensagem' => 'Imagem cadastrada com sucesso'];  

    } 

    public function show($id){

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()){

            return \Response::json(['errors' => [ $validator->errors()->all() ]], 404);
        }        

        $ticket = TicketImagem::select('tickets_imagem.id', 'tickets_imagem.imagem as imagem')
        ->where('tickets_imagem.id', $id)->first();     

        return $ticket;
    }

    public function destroy($id){

        $validator = Validator::make( ['id' => $id], [            
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()){
            return \Response::json(['errors' => [ $validator->errors()->all() ]], 404);
        }
        
        $ticket = TicketImagem::where('id',$id)->first();

        if( \Auth()->user()->id != $ticket->usuario_inclusao_id){
            return abort(403);
        }

        $ticket->delete();        

        $this->ticket_historico->historicoImagemTicketDelete( \Auth()->user()->id, $ticket->ticket_id );

        return ['status'=>true, 'mensagem' => 'Imagem deletada com sucesso'];

    }

    public function imagemDados($id){

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()){

            return \Response::json(['errors' => [ $validator->errors()->all() ]], 404);
        }        

        $ticket = TicketImagem::select('tickets_imagem.id', 'tickets_imagem.texto as descricao', 'tickets_imagem.created_at', 'usuarios.nome', 'tickets_imagem.usuario_inclusao_id')
        ->leftJoin('usuarios', 'usuarios.id', '=', 'tickets_imagem.usuario_inclusao_id')
        ->where('tickets_imagem.id', $id)->first();

        //verifica se usuario que subiu imagem eh o mesmo que esta logado para poder excluí-la
        $permissaoImagemTicketExcluir = false;
        if( \Auth()->user()->id == $ticket->usuario_inclusao_id){
            $permissaoImagemTicketExcluir = true;
        }
        
        $ticket->__set( 'permissaoImagemTicketExcluir', $permissaoImagemTicketExcluir);        

        return $ticket;
    }    
}
