<?php

namespace App\Http\Controllers\RH;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\ImagemController;
use App\Http\Controllers\Core\ExcelController;

use App\Http\Requests\RH\FuncionarioRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

use App\Models\RH\FuncionarioHistorico;
use App\Models\RH\Funcionario;
use App\Models\RH\FuncionarioAvatar;
use App\Models\RH\Cargos;
use App\Models\RH\Departamento;
use App\Models\BaseDeConhecimento\PublicacaoCargo;
use App\Models\Configuracao\Usuario;
use Illuminate\Support\Facades\DB;


class FuncionarioController extends Controller
{

    private $entidade = 'Funcionário';
    private $funcionario;
    private $funcionarioAvatar;
 
    public function __construct( 
                                Cargos $cargos,
                                    Funcionario $funcionario,
                                        FuncionarioAvatar $funcionarioAvatar,
                                            ImagemController $imagem
                         )
    {
        $this->funcionario = $funcionario;
        $this->funcionarioAvatar = $funcionarioAvatar;
        $this->cargos = $cargos;        
        $this->imagem = $imagem;
    }

    /**
     * Tela de pesquisa sem filtro
     */
    public function index()//ok
    {        
        $this->autorizacao( 'RH_FUNCIONARIO_VISUALIZAR' );

        $gestores = $this->funcionario->getFuncionariosAtivos();
        $cargos = $this->cargos->getCargos();
        
        return view('rh.funcionario.index', 
            [            
                'gestores' => $gestores, 
                'cargos' => $cargos,
                'can' => [ 
                    'abas'      => \Auth::user()->can( 'RH_FUNCIONARIO_ABA_DADOS_FUNCIONARIO' ),                
                    'status'    => \Auth::user()->can( 'RH_FUNCIONARIO_STATUS' ),
                    'editar'    => \Auth::user()->can( 'RH_FUNCIONARIO_EDITAR' ),
                    'cadastrar' => \Auth::user()->can( 'RH_FUNCIONARIO_CADASTRAR' ),
                    'avatar'    => \Auth::user()->can( 'RH_FUNCIONARIO_EDITAR_AVATAR' ),
                    'exportar'  => \Auth::user()->can( 'RH_FUNCIONARIO_EXPORTAR_DADOS_FUNCIONARIO' )
                ],
                'migalhas' => $this->migalhaDePao( 'INDEX' ) ]);
    }

    /**
     * Tela de pesquisa com filtro
     */
    public function search(Request $request)//ok
    {      
        $this->autorizacao( 'RH_FUNCIONARIO_VISUALIZAR' );

        $funcionarios = $this->funcionario->getFuncionarios($request);

        return [
           'funcionarios' => $funcionarios
        ];
        
    }

    /**
     * Muda o Status
     */
    public function destroy( $id )//ok
    {   
        $this->autorizacao( 'RH_FUNCIONARIO_STATUS' );

        $funcionario = $this->funcionario->where('id', $id)->first();

        if( isset($funcionario) )
        {
            $ativo = !$funcionario->ativo;

            $this->funcionario->
                    where('id', '=',$id)
                    ->update([
                    'usuario_alteracao_id' => \Auth::user()->id,
                    'ativo' => $ativo,
                    ]);

            return \Response::json($id, 200);
        }else{
            return \Response::json(['errors' => ['errors' => [ $this->errors()->msgDestroy( $this->entidade ) ] ] ] ,404);
        }
    }

    /**
     * retorna a View() para cadastrar Funcionario
     */
    public function create()//ok
    {
        $this->autorizacao( 'RH_FUNCIONARIO_CADASTRAR' );

        $gestores = $this->funcionario->getFuncionariosAtivos();
        $cargos = $this->cargos->getCargos();

        return view('rh.funcionario.cadastrar', [
            'gestores' => $gestores, 
            'cargos' => $cargos,
            'can' => [ 
                'cadastrar' => \Auth::user()->can( 'RH_FUNCIONARIO_CADASTRAR' )
            ],
            'migalhas' => $this->migalhaDePao()
        ]);
    }

    /**
     * Inserir dados de novo funcionario
     */
    public function store( FuncionarioRequest $request )//ok
    {
        $this->autorizacao( 'RH_FUNCIONARIO_CADASTRAR' );

       try{

            $funcionarioId = $this->funcionario->insert($request);

        }catch(\Exception $e){
            return \Response::json(['errors' => [ 'erros' => [$this->errors()->msgStore( $this->entidade ) ]]], 404);
        }

        return ['status'=>true, 'mensagem' => $this->success()->msgStore( $this->entidade ), 'id' => $funcionarioId ];
    }

    /**
     * Retorna dados do funcionárop para edição
     */
    public function edit( $id )//ok
    {
        $this->autorizacao('RH_FUNCIONARIO_EDITAR');

        $funcionario = $this->funcionario->getFuncionario( $id )->first();

        if( empty( $funcionario ) )
        {
            return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
        }

        $gestores = $this->funcionario->getFuncionariosAtivos();
        $cargos = $this->cargos->getCargos();

        return view('rh.funcionario.editar', [
            'funcionario' => $funcionario, 
            'gestores' => $gestores, 
            'cargos' => $cargos,
            'can' => [ 
                'cadastrar' => \Auth::user()->can( 'RH_FUNCIONARIO_EDITAR' )
            ],
            'migalhas' => $this->migalhaDePao()
        ]);

       
    }

    /**
     * Atualiza dados do Funcionario
     */
    public function update( FuncionarioRequest $request, $id )//ok
    {
        $this->autorizacao('RH_FUNCIONARIO_EDITAR');
        
        $funcionario = $this->funcionario->getFuncionario( $id )->first();
           
        if( empty( $funcionario ) )
        {
            return \Response::json(['errors' => ['errors' => [$this->errors()->msgUpdate( $this->entidade )] ] ], 500);
        } 

        try{

            $this->funcionario->updates($request, $id);
        
        }
        catch(\Exception $e)
        {
            return \Response::json(['errors' => ['errors' => [$this->errors()->msgUpdate( $this->entidade )] ] ], 404);
        }

        return ['mensagem' => $this->success()->msgUpdate( $this->entidade ), 'id' => $id ];
    }





    public function changeAvatar (Request $request, $id) {//ok

        $this->autorizacao('RH_FUNCIONARIO_EDITAR_AVATAR');
        
        DB::beginTransaction();
        try{

            if( isset($request->image) )
            {
                $file = $request->image;
                
                $base64 = $this->imagem->geraImagemBase64( $file, 150, 'png' );
                
                // $base64_miniatura = $this->imagem->geraImagemThumbBase64( $file, 'png' );
               
                 $img = \Image::make($base64);

                // Redimensiona a cópia preservando as proporções
                $img->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Corta a imagem para ficar 100x100
                $img->crop(100, 100, 0, 0);

                // Converte para base64
                $base64_miniatura = base64_encode($img->encode('png'));



                $this->funcionario->where('id', '=', $id)->update(
                    [
                        'avatar_grande' => $base64, 
                        'avatar_pequeno' => $base64_miniatura,
                        'usuario_alteracao_id' => \Auth::user()->id,
                    ]);

                $this->funcionarioAvatar->insert([
                        'avatar_grande' => $base64, 
                        'avatar_pequeno' => $base64_miniatura,
                        'usuario_inclusao_id' => \Auth::user()->id,
                        'funcionario_id' => $id
                ]);


            }else{
                return \Response::json( ['errors' =>  ['errors' => [ $this->errors()->msgAvatar( ) ] ] ] ,404);

            }
          
            DB::commit();

        }catch( \Exception $e ){
            
            DB::rollback();
            return \Response::json( ['errors' =>  ['errors' => [ $this->errors()->msgAvatar( ) ] ] ] ,404);
            
        }
        
        return ['mensagem' => $this->success()->msgUpdate( $this->entidade )];

    }


    /**
     * Visualizar dados do usuario
     */
    public function show( $id )//ok
    {
        $this->autorizacao( 'RH_FUNCIONARIO_VISUALIZAR' );
                   
            $funcionario =  $this->funcionario->getFuncionario($id)->first();
            
            if( !isset( $funcionario ) )
            {
                return $this->paginaNaoEncontrada( [
                    'titulo' => $this->entidade, 
                    'descricao' => $this->errors()->descricaoVisualizar( $this->entidade ), 
                    'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                    'migalhas' => $this->migalhaDePao()
                ]);
            }
           
            
            if ($funcionario->cargo_id !== null) {                
                
                $funcionarioCargo = Cargos::select('departamento_id', 'nome')->where('id', $funcionario->cargo_id)->get()[0];
                $funcionarioDepartamento = Departamento::select('nome')->where('id', $funcionarioCargo->departamento_id)->get()[0];
          
            }else{
                $funcionarioCargo = New Funcionario;
                $funcionarioCargo->nome = '-';
                $funcionarioDepartamento['nome'] = '-';
            }   

            if ($funcionario->gestor_id !== null) {                                
                $funcionarioGestor = Funcionario::select('nome')->where('id', $funcionario->gestor_id)->get()[0];
            }else{

                $funcionarioGestor = New Funcionario;
                $funcionarioGestor->nome = '-';
            }
            
            $usuario = Usuario::where('funcionario_id', $funcionario->id)->get();

            if ( count($usuario) > 0 ) {
                $funcionario['usuario'] = $usuario[0]->usuario;
            }
            else {
                $funcionario['usuario'] = '';
            }            
            
            $funcionario_avatar = new FuncionarioAvatar();
            $historico_avatar = $funcionario_avatar->getHistoricoAvatares($id);

       

                 
        
        return view( 'rh.funcionario.visualizar', [
            'dados' => [
                'funcionario' => $funcionario,                
                'funcionarioCargo' => $funcionarioCargo,
                'funcionarioDepartamento' => $funcionarioDepartamento,
                'funcionarioGestor' => $funcionarioGestor,
                'historicoAvatar' => $historico_avatar
            ],
            'migalhas' => $this->migalhaDePao(),
            'can' => [ 
                'ABA_DADOS_PESSOAIS'    => \Auth::user()->can( 'RH_FUNCIONARIO_ABA_DADOS_PESSOAIS' ),                
                'ABA_CONTATO'           => \Auth::user()->can( 'RH_FUNCIONARIO_ABA_CONTATO' ),
                'ABA_DADOS_FUNCIONARIO' => \Auth::user()->can( 'RH_FUNCIONARIO_ABA_DADOS_FUNCIONARIO' ),
                'ABA_DADOS_DE_CADASTRO' => \Auth::user()->can( 'RH_FUNCIONARIO_ABA_DADOS_DE_CADASTRO' ),
                'BOTAO_EDITAR'          => \Auth::user()->can( 'RH_FUNCIONARIO_EDITAR' ),
                'ABA_HISTORICO_AVATAR'  => \Auth::user()->can( 'RH_FUNCIONARIO_ABA_HISTORICO_AVATAR' ),
                'BOTAO_EXCLUIR_AVATAR'  => \Auth::user()->can( 'RH_FUNCIONARIO_EXCLUIR_HISTORICO_AVATAR' )
            ]
        ] );
    }

    public function getFuncionariosAniversario(){

        if( empty(\Auth::user()) )
        {
            abort(401, 'Usuário não logado.');
        }

        $funcionarios = array();
        $funcionario = new Funcionario();

        $funcionarios['funcionarios'] = $funcionario->proximosAniversarios();
        $funcionarios['funcionarios_niver'] = $funcionario->aniversariantes();
        
        return $funcionarios;

    }

    public function downloadExcel( Request $request, ExcelController $excel, $type ) {    

        $this->autorizacao( 'RH_FUNCIONARIO_EXPORTAR_DADOS_FUNCIONARIO' );
        
        /**
     * Select da tabela para exportacao de excel(csv, xlsx)
     */
        $select_excel = "funcionarios.created_at AS data, funcionarios.created_at AS hora, funcionarios.nome, funcionarios.nome_completo, funcionarios.email, funcionarios.dt_nascimento AS dt_nascimento, funcionarios.celular_pessoal, funcionarios.telefone_comercial, funcionarios.ramal, funcionarios.ativo";
        
        $titulo_excel = array( 'Data', 'Hora', 'Nome', 'Nome Completo', 'Email', 'Data de Nascimento', 'Celular Pessoal', 'Telefone Comercial', 'Ramal', 'Ativo');
 
        $string = new Str;
       
        $query = Funcionario::query();    

        $query = $query->selectRaw( $select_excel );

        if (Input::has('nome')) {                
            $query = $query->whereRaw( "sem_acento(funcionarios.nome) LIKE sem_acento('%".$string->upper($request->nome)."%')" );            
        }

        if (Input::has('email')) {            
            $query = $query->whereRaw( "funcionarios.email LIKE '%".$request->email."%'" );
        }        

        if (Input::has('ativo') && $request->ativo != 'todos') {            
            $query = $query->where( "funcionarios.ativo", "=", $request->ativo);
        }

        if ( !(is_null(Input::get('cargo')))) {            
            $query = $query->where( "funcionarios.cargo_id", "=", $request->cargo);
        }

        if ( !(is_null(Input::get('gestor')))) {       

             
            $query = $query->where( "funcionarios.gestor_id", "=", $request->gestor);
        }
        

        $query = $query->orderByRaw('data desc, hora desc'); 
              
        $dados = $query->get();

        foreach ($dados as $dado) {

            $ativo = $dado['ativo'] ? 'SIM' : 'NÃO';

            $dado->__set('ativo',  $ativo);


            $dado->__set( 'hora', $this->date()->formataHoraExcel($dado['data']) );
            $dado->__set( 'data', $this->date()->formataDataExcel($dado['data']) );

            $dado->__set( 'dt_nascimento', $this->date()->formataDataExcel($dado['dt_nascimento']) );
            // $dado->__set( 'hora_update', $this->date()->formataHoraExcel($dado['hora_update']) );



        }

        $formato = [
                'A' => "dd/mm/yyyy", 
                'B' => "hh:mm:ss", 
                'F' => "dd/mm/yyyy", 
                    ];

        return $excel->downloadExcel($type, $dados->toArray(), 'log_funcionarios_', $titulo_excel, $formato);       

    }

    public function getAvatarPequeno( $id )
    {
        if ( !empty(\Auth::user())){
            
            $base64 = $this->funcionario->getAvatarPequenoBase( $id );

            if ( !empty($base64) )
            {
                return ImagemController::getResponse($base64);
            }
    
            return null;
        }
        abort(401, 'Usuário não logado.');

    } 

    public function getAvatarGrande( $id )
    {
        if ( !empty(\Auth::user())){
            
            $base64 = $this->funcionario->getAvatarGrandeBase( $id );

            if ( !empty($base64) )
            {
                return ImagemController::getResponse($base64);
            }
    
            return null;
        }
        abort(401, 'Usuário não logado.');

    } 
    
    public function getFuncionariosComPermissaoNaPublicacaoLikeNome( $modulo, $publicacaoId, $funcionario ) {

        if( $modulo = 'publicacao' ) {

            return $this->funcionario->getFuncionariosComPermissaoNaPublicacaoLikeNome( $funcionario, $publicacaoId );
            
        }        

    }    
}