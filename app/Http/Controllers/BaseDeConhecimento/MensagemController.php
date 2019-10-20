<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Models\BaseDeConhecimento\PublicacaoMensagem;
use App\Models\Configuracao\Usuario;
use App\Models\Configuracao\Sistema\Parametro;
use App\Models\RH\Funcionario;

use Event;
use App\Events\NotificacaoEvent;

class MensagemController extends Controller
{

    private $publicacaoMensagem;
    private $usuario;
    private $parametro;
    private $errors;
    private $success;
    private $entidade = 'Mensagem';
   
    public function __construct( Errors $errors,
                                    Success $success,
                                        PublicacaoMensagem $publicacaoMensagem,
                                            Usuario $usuario, 
                                                Parametro $parametro,
                                                    Funcionario $funcionario   )
    {
    	 
        $this->publicacaoMensagem = $publicacaoMensagem;
        $this->usuario = $usuario;
        $this->funcionario = $funcionario;
        $this->parametro = $parametro;
        $this->errors = $errors;
        $this->success = $success;
    }

    /**
     * Método que retorna as mesnagens das publicações de acordo com o filtro
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->autorizacao( 'BASE_MENSAGENS_VISUALIZAR' );

        return view('base-de-conhecimento.mensagens', [
            'migalhas' => $this->migalhaDePao('false'),
            'funcionarios' => $this->funcionario->getFuncionariosAtivos(),
            'id_mensagem' => 0
        ]);
    }

    /**
     * Método que retorna a mensagem do get
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->autorizacao( 'BASE_MENSAGENS_VISUALIZAR' );

        return view('base-de-conhecimento.mensagens', [
            'migalhas' => $this->migalhaDePao('false'),
            'funcionarios' => $this->funcionario->getFuncionariosAtivos(),
            'id_mensagem' => $id
        ]);
    }
    
    /**
     * Método que envia uma mensagem para Publicação
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $id_mensagem = $this->publicacaoMensagem->insertPublicacaoMensagem( $request );

        if( isset($id_mensagem) )
        {
            event(
                new NotificacaoEvent( $id_mensagem, ['notificacao' => $this->getNotificacao( $id_mensagem )] ) 
            );
        }

        return [
            'mensagem' => $this->entidade.' enviada com sucesso.', 
            'id' => $request->publicacao_id
        ];
    }

    /**
     * getNotificacao retorna os dados para gerar a notificação
     * @param  integer $id_mensagem ID da mensagem gerada por um usuário para uma publicação
     * @return array                dados gerar a notificação
     */
    private function getNotificacao( $id_mensagem )
    {
        return [
            'titulo' => 'Base de conhecimento',
            'mensagem' => 'Nova mensagem!',
            'modulo' => 'base_de_conhecimento',
            'url'  => '/base-de-conhecimento/mensagens/'.$id_mensagem,
            'icone' => 'comment',
            'cor' => '#f39c12',
            'usuario' => $this->formataIdsUsuario( $this->getUsuariosDepartamentosParametros() ),
        ];
    }

    /**
     * getUsuariosDepartamentosParametros filtra os usuários por departamento
     * @return App\Models\Configuracao\Usuario colletions de usuários
     */
    private function getUsuariosDepartamentosParametros()
    {
        $parametros = $this->parametro->select('valor_texto')->where('nome', '=', 'BC_NOTIFICACAO_DEPARTAMENTOS')->first();
        $usuarios = $this->usuario->getUsuariosDepartamentos();

        if( isset($parametros) && isset($parametros->valor_texto) && $parametros->valor_texto != '' )
        {
            return $usuarios->whereIn('departamentos_id', explode(',', $parametros->valor_texto));
        }

        return $usuarios;
    }

    /**
     * formataIdsUsuario formata para retornar somente o id os usuários
     * @param  App\Models\Configuracao\Usuario $collections colletions de usuários
     * @return Array id's de usuário             
     */
    private function formataIdsUsuario( $collections )
    {
        $dados = [];

        foreach ($collections as $key => $value)
        {
            array_push($dados, ['id' => $value->usuarios_id]);
        }

        return $dados;
    }

    public function getMensagens(Request $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );
        
        if( isset($request->data_de) && isset($request->data_ate) )
        {
            return $this->formataDataInteracoes( $this->publicacaoMensagem->getMensagens( $request ) );
        }

        return [];
    }

    public function getMensagem( $id )
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );
        
        if( isset($id) && is_numeric($id) )
        {
            return $this->formataDataInteracoes( $this->publicacaoMensagem->getMensagem( $id ) );
        }

        return [];
    }


    private function formataDataInteracoes( $mensagens )
    {
        if( isset( $mensagens ) && count($mensagens) > 0 )
        {
            $mensagens = $mensagens->map(function ($mensagem)
            {
                $mensagem->mensagem = $this->formatString()->ajustaLink($mensagem->mensagem);
                $mensagem->data_interacao = $this->date()->dataInteracoes( $mensagem->data_interacao );
                return $mensagem;
            });           

            return $mensagens;
        }

        return [];
    }

    public function setResposta (Request $request){

        $this->autorizacao( 'BASE_MENSAGENS_VISUALIZAR' );
        $this->publicacaoMensagem::whereId($request->mensagem_id)
         ->update( 
            [ 
                'respondida' => 'true',
                'resposta' => $request->resposta,
                'usuario_resposta_id' => \Auth::user()->id,
                'dt_resposta' => date("Y-m-d H:i:s")
            ] 
        );
         return [
            'mensagem' => $this->success->msgStore( $this->entidade )
        ];

    }
}