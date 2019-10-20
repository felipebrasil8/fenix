<?php

namespace App\Http\Controllers\Configuracao;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Http\Requests\Configuracao\UsuarioRequest;
use App\Http\Requests\Configuracao\ChangePasswordRequest;
use App\Http\Requests\Configuracao\NovaSenhaRequest;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Http\Controllers\Core\ImagemController;

use App\Repositories\Configuracao\UsuarioRepositoryInterface;
use App\Repositories\Configuracao\PerfilRepositoryInterface;
use App\Repositories\RH\FuncionarioRepositoryInterface;
use App\Repositories\Configuracao\UsuarioViewRepositoryInterface;
use App\Repositories\Configuracao\Sistema\PoliticaSenhaRepositoryInterface;

use Event;
use App\Events\NotificacaoEvent;
use App\Models\RH\Funcionario;
use \App\Models\Configuracao\Usuario;

class UsuarioController extends Controller
{
    protected $repository;
    protected $perfilRepository;
    protected $funcionarioRepository;
    protected $usuarioViewRepository;
    protected $politicaSenhaRepository;

    private $entidade = 'Usuário';
    private $errors;
    private $success;
    private $strAutorizacaoModulo = 'CONFIGURACAO_USUARIO_';
    private $usuario;

    public function __construct(
        UsuarioRepositoryInterface $repository,
            PerfilRepositoryInterface $perfilRepository,
                FuncionarioRepositoryInterface $funcionarioRepository,
                    UsuarioViewRepositoryInterface $usuarioViewRepository,
                        PoliticaSenhaRepositoryInterface $politicaSenhaRepository,
                        Funcionario $funcionario,
                        Usuario $usuario,
                            Errors $errors,
                                Success $success)
    {
        $this->repository = $repository;
        $this->perfilRepository = $perfilRepository;
        $this->funcionarioRepository = $funcionarioRepository;
        $this->usuarioViewRepository = $usuarioViewRepository;
        $this->politicaSenhaRepository = $politicaSenhaRepository;
        $this->usuario = $usuario;
        $this->funcionario = $funcionario;
        $this->errors = $errors;
        $this->success = $success;
    }

    public function index()//ok
    {
        $this->autorizacao($this->strAutorizacaoModulo.'VISUALIZAR');

       try{

            $perfis = $this->perfilClass();
           
        }catch(\Exception $e){
            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
            ] );
        }
        
        return view( 'configuracao.usuario.index', [ 
            'perfis' => $perfis,
            'ativo'=>'true', 
            'filtro' => false, 
            'migalhas' => $this->migalhaDePao( 'INDEX' ) 
        ] );
    }

    /**
     * Pesquisa Usuario por id
     */
    public function show( $id )//ok
    {
        $this->autorizacao($this->strAutorizacaoModulo.'VISUALIZAR');

        try{

            //verifica se usuario existe, se nao existe retorna uma exception
            $usuario = $this->repository->find($id, [
                'id', 
                'nome', 
                'usuario', 
                'perfil_id', 
                'funcionario_id', 
                'usuario_inclusao_id', 
                'usuario_alteracao_id', 
                'updated_at', 
                'created_at'
            ])->makeHidden( [
                'perfil_id', 
                'funcionario_id',
                'usuario_inclusao_id',
                'usuario_alteracao_id'
            ]);

            $perfil = $this->perfilRepository->find($usuario->perfil_id);

            $usuarioInclusao = $this->repository->scopeQuery(function($query){
                    return $query->first();
                })->findWhere([ ['id', '=', $usuario->usuario_inclusao_id] ], ['nome']);

            $usuarioAlteracao = $this->repository->scopeQuery(function($query){
                    return $query->first(); 
                })->findWhere([ ['id', '=', $usuario->usuario_alteracao_id] ], ['nome']);

            $usuario->__set("perfil", $perfil->nome);
            $usuario->__set("usuarioInclusao", $usuarioInclusao);
            $usuario->__set("usuarioAlteracao", $usuarioAlteracao);

            if(!is_null( $usuario->funcionario_id ))
            {
                $funcionario = $this->funcionarioRepository->find($usuario->funcionario_id, ['nome']);

                $usuario->__set("funcionario", $funcionario->nome);
            }
            else
            {
                $usuario->__set("funcionario", '-');
            }
            
        }catch(\Exception $e){
            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
            ] );
        }

        return view('configuracao.usuario.visualizar', [ 'usuario' => $usuario, 'migalhas' => $this->migalhaDePao() ]);
    }

    /**
     * metodo de busca
     */
    public function search()//ok
    {

        $this->autorizacao($this->strAutorizacaoModulo.'VISUALIZAR');

        try{

            $string = new Str;
            $nome = $string->upper( Input::get('nome') );
            $usuario = $string->lower( Input::get('usuario') );
            $order = input::get('ordem');

            $usuarios = $this->usuarioViewRepository->paginacao(
                input::get('limite'), 
                input::get('to'), 
                [ 
                    [\DB::raw("sem_acento(\"nome\")"), 'LIKE', \DB::raw("sem_acento('%".$nome."%')") ],
                    [\DB::raw("sem_acento(\"usuario\")"), 'LIKE', \DB::raw("sem_acento('%".$usuario."%')") ],
                    ['ativo', '=', input::get('ativo') ], 
                    ['perfil_id', 
                        is_null(Input::get('perfil.id'))?'!=':'=',
                        is_null(Input::get('perfil.id'))?0:(int)Input::get('perfil.id')
                    ]
                ],
                input::get('coluna'),
                $order
                ,['id', 'funcionario_id', 'perfil_id', 'nome', 'usuario', 'ativo', 'nome_perfil', 'senha_alterada', 'senha_alterada' ]   
                );

            $usuarios = $this->formataUsuario( $usuarios );

        }catch(\Exception $e){
            
            return \Response::json( ['errors' => $this->errors->msgSearch()] ,404);
        }

        return [
            'usuarios' => $usuarios,                
            'perfil' => Input::has('perfil.id') ? Input::get('perfil.id') : 0                
        ];     
    }

    /**
     * retorna a View() para cadastrar Usuario
     */
    public function create(){//ok

        $this->autorizacao($this->strAutorizacaoModulo.'CADASTRAR');

        try{

            //dados para o select options
            $perfis = $this->perfilClass();
            $funcionarios = $this->funcionariosSemUsuariosClass();
        }catch( \Exception $e ){

            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoCadastrar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
                ] );            
        }

        return view('configuracao.usuario.cadastrar', [
            'perfis' => $perfis, 
            'migalhas' => $this->migalhaDePao(), 
            'funcionarios' => $funcionarios
        ] );
    }


    /**
     * Insere dados de novo usuario
     */
    public function store(UsuarioRequest $request){//ok

        $this->autorizacao($this->strAutorizacaoModulo.'CADASTRAR');

        try{

            $politicaSenha = $this->politicaSenhaClass();

            $validacaoSenha = $politicaSenha[0]->complexidadeSenha($request->senha);

            if( count($validacaoSenha) == 0 )
            {
                $string = new Str;

                $usuario = $this->repository->create( 
                    [ 
                        'usuario' => $string->lower( $request->usuario ),
                        'nome' => $string->upper($request->nome),
                        'perfil_id' => $request->perfil['id'],
                        'funcionario_id' => $request->funcionario['id'],
                        'password' => bcrypt($request->senha),
                    ] 
                );
            }
            else
            {
                return \Response::json(['errors' => $validacaoSenha], 404);
            }

        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgStore( $this->entidade ) ] ,404);
        }

        return ['status'=>true, 'mensagem' => $this->success->msgStore( $this->entidade ), 'id' => $usuario->id ];
    }

    /**
     * Retorna dados do usuario para edição
     */
    public function edit( $id ){//ok

        $this->autorizacao($this->strAutorizacaoModulo.'EDITAR');

        try{

            $usuario = $this->repository->find($id, ['id', 'nome', 'usuario', 'perfil_id', 'funcionario_id']);

            $perfis = $this->perfilClass();

            $func_id = $this->repository->find($id, ['funcionario_id']);

            $func_atual_array = $this->funcionarioRepository->find($func_id, ['id', 'nome'])->toArray();
            //$func_atual_array = $func_atual->toArray();

            $funcionarios_sem_usuarios = $this->funcionariosSemUsuariosClass($func_id)->toArray();
            //$funcionarios_sem_usuarios = $funcionarios_sem_usuarios->toArray();
                                    
            $func_selected = $this->funcionarioRepository->findWhere([ ['id', '=',  $usuario['funcionario_id'] ]  ], ['id', 'nome']);

            $perf_selected = $this->perfilRepository->findWhere([ ['ativo', '=', 'TRUE'], ['id', '=',  $usuario['perfil_id'] ]  ], ['id', 'nome']);

            if( $func_atual_array === $funcionarios_sem_usuarios ){

                $funcionarios_lista = $funcionarios_sem_usuarios;

            }else{

                //prepara $func_atual_array para unir com $funcionarios_sem_usuarios
                $funcionarios_lista = array_merge(
                    $funcionarios_sem_usuarios, 
                    $func_atual_array
                    );                
            }

        }catch(\Exception $e){

            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade,
                'descricao' => $this->errors->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
                ] );
        }
        
        return view('configuracao.usuario.editar', [
            'usuario' => $usuario, //id, nome, usuario
            'perfis' => $perfis, //lista perfis
            'funcionarios' => json_encode($funcionarios_lista), //lista funcionarios
            'func_selected' => $func_selected, //funcionario selecionado
            'perf_selected' => $perf_selected, //perfil selecionado
            'migalhas' => $this->migalhaDePao()
            ]);
    }

    /**
     * Atualiza dados de Usuario
     */
    public function update( UsuarioRequest $request, $id ){//ok

        $this->autorizacao($this->strAutorizacaoModulo.'EDITAR');

        try{

            $string = new Str;

            $request['usuario'] = $string->lower($request->usuario);
            $request['nome'] = $string->upper($request->nome);
            $request['perfil_id'] = $request->perfil['id'];
            $request['funcionario_id'] = $request->funcionario['id'];

            $usuario = $this->repository->update($request->all(), $id);

        } catch(\Exception $e){

            return \Response::json(['errors' => [$this->errors->msgUpdate( $this->entidade ) ]] ,404);
        }
        
        return ['status'=>true, 'mensagem' => $this->success->msgUpdate( $this->entidade ), 'id' => $usuario->id ];
    }

    public function changePassword (ChangePasswordRequest $request, $id) {

        try{

            if ( empty(\Auth::user()->id) ) {
                return response()->json([ 'status'=>false, 'msg' => 'Usuário não logado.' ], 400);
            }

            if (\Auth::user()->id != $id) {
                return response()->json([ 'status'=>false, 'msg' => 'Não possui autorização.' ], 401);
            }

            $usuario = $this->repository->find($id);

            if (password_verify($request->oldPassword, $usuario->password)) {

                $politicaSenha = $this->politicaSenhaClass();
                $validacaoSenha = $politicaSenha[0]->complexidadeSenha($request->newPassword);

                if( count($validacaoSenha) == 0 )
                {
                    $usuario->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
                    $usuario->visualizado_senha_alterada = true;
                    $usuario->senha_alterada = true;
                    $usuario->save();

                    return response()->json([ 'success' => 'Senha alterada com sucesso.' ], 200);
                }
                else
                {
                    return \Response::json(['errors' => $validacaoSenha], 404);
                }
            }
            else {
                return response()->json([ 'errors' => ['Senha antiga inválida.'] ], 422);
            }

        }catch( \Exception $e ){

            return \Response::json(['errors' => $e->getMessage() ] ,404);
        }
    }

    public function novaSenha (NovaSenhaRequest $request, $id) {

        $this->autorizacao($this->strAutorizacaoModulo.'ALTERAR_SENHA');

        try{

            $usuario = $this->repository->find($id);

            if ($usuario)
            {
                if( $request->solicitar_alteracao == true )
                {
                    $usuario->visualizado_senha_alterada = true;
                    $usuario->senha_alterada = false;
                }
                else
                {
                    $usuario->visualizado_senha_alterada = true;
                    $usuario->senha_alterada = true;
                }

                $politicaSenha = $this->politicaSenhaClass();
                $validacaoSenha = $politicaSenha[0]->complexidadeSenha($request->newPassword);

                if( count($validacaoSenha) == 0 )
                {
                    $usuario->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
                    $usuario->save();
                }
                else
                {
                    return \Response::json(['errors' => $validacaoSenha], 404);
                }

                return response()->json([ 'success' => 'Senha alterada com sucesso.' ], 200);

            } else {

                return response()->json([ 'errors' => 'Usuário inválido.' ], 422);
            }

        }catch( \Exception $e ){

            return \Response::json(['errors' => $e->getMessage() ] ,404);
        }
    }

    public function solicitarNovaSenha (UsuarioRequest $request, $id)
    {

        if ( empty(\Auth::user()->id) ) {
            return response()->json([ 'status'=>false, 'msg' => 'Usuário não logado.' ], 400);
        }

        if ( \Auth::user()->id != $id  )
        {
            $this->autorizacao($this->strAutorizacaoModulo.'SOLICITAR_SENHA');
        }
        
        try{
            
            $usuario = $this->repository->find($id);

            if ( $usuario ) {
                
                $usuario->visualizado_senha_alterada = $request->visualizado_senha_alterada;
                $usuario->senha_alterada = $request->senha_alterada;

                $usuario->save();

                return response()->json([ 'success' => 'Solicitação de senha alterada com sucesso.' ], 200);

            } else {

                return response()->json([ 'errors' => 'Usuário inválido.' ], 422);
            }

        }catch( \Exception $e ){

            return \Response::json(['errors' => $e->getMessage() ] ,404);
        }
    }

    /**
     * Deleta Usuario
     */
    public function destroy( $id )//ok
    {
        $this->autorizacao($this->strAutorizacaoModulo.'STATUS');

        try{

            return $this->repository->destroy( $id );                
            
        }catch(\Exception $e){

            return \Response::json(['errors' => $this->errors->msgDestroy( $this->entidade ) ] ,404);            
        }
    }

    private function perfilClass(){
        
        try{

            $perfis = $this->perfilRepository->scopeQuery(function($query){

                return $query->orderBy('nome','asc'); 

            })->findWhere([ ['ativo', '=', 'TRUE'] ], ['id', 'nome']);

        }catch(\Exception $e){
            
            throw new Exception("Error:  perfilClass", 1);
            return ;
            
        }       

        return $perfis;
    }

    private function politicaSenhaClass(){
        
        try{

            $politicaSenha = $this->politicaSenhaRepository->paginacao(null, null, [['ativo', '=', 'TRUE']], 'id', 'desc', [
                'id', 
                'tamanho_minimo', 
                'qtde_minima_letras', 
                'qtde_minima_numeros', 
                'qtde_minima_especial', 
                'caractere_especial', 
                'maiusculo_minusculo'
            ]);

        }catch(\Exception $e){
            
            throw new Exception("Error: politicaSenhaClass", 1);
            return ;
            
        }       

        return $politicaSenha;
    }

    /**
     * Não apagar estas queries
     */

    // private function funcionarioClass(){

    //     try{

    //         $funcionarios = $this->funcionarioRepository->scopeQuery(function($query){

    //             return $query->orderBy('nome','asc'); 

    //         })->findWhere([ ['ativo', '=', 'TRUE'] ], ['id', 'nome']);

    //     }catch(\Exception $e){

    //         throw new Exception("Error: funcionarioClass", 1);
    //         return ;
    //     }
        
    //     return $funcionarios;
    // }

    /**
     * Este metodo nao esta utilizando repostory!!!
     */
    private function funcionariosSemUsuariosClass($id=null) {

        try{
            //select * from funcionarios where ativo = true and id not in ( select funcionario_id from usuarios where ativo = true and funcionario_id is not null )
            $funcionarios = \App\Models\RH\Funcionario::where('funcionarios.ativo', true)
                ->whereNotIn('id', function($query)
                {
                    $query->select('funcionario_id')
                        ->from('usuarios')
                        ->where('usuarios.ativo', true)
                        ->where('funcionario_id', '!=', null);
                    
                })->select('id', 'nome')->get();
        }
        catch(\Exception $e)
        {
            throw new \Exception("Error: funcionariosSemUsuariosClass", 1);
            return ;
        }

        return $funcionarios;
    }    

    /**
     * Não apagar estas queries
     */

    /*
    private function funcionariosSemUsuariosClass($id=null) {

        try{
           
            $funcionarios = $this->funcionarioRepository->scopeQuery(function($query){

                return $query->orderBy('nome','asc');

            })->findWhereNotIn( 'id',

                $this->repository->scopeQuery(function($query){

                    return $query->orderBy('funcionario_id');
                    
                })->findWhere([['ativo', '=', 'TRUE'], ['funcionario_id','!=', NULL]], ['funcionario_id'])->toArray()
            );

        }catch(\Exception $e){

            throw new \Exception("Error: funcionariosSemUsuariosClass", 1);
            return ;
        }

        return $funcionarios;
    } 
    */

    private function formataUsuario( $usuarios )
    {
        foreach ($usuarios as $usuario)
        {
            $perfil = $this->perfilRepository->find($usuario->perfil_id, ['nome']);
            $usuario->__set("perfil_nome", $perfil->nome);

            if( $usuario->senha_alterada == false )
            {
                $icone = 'check';
                $texto = 'Será solicitada a alteração de senha no próximo acesso, clique para alterar.';
            }
            else
            {
                $icone = 'close';
                $texto = 'Não será solicitada a alteração de senha no próximo acesso, clique para alterar.';
            }

            $usuario->__set("solicitar_senha_icone", $icone);
            $usuario->__set("solicitar_senha_texto", $texto);
        }

        return $usuarios;
    }


    public function getAvatarPequeno( $id )
    {
        if ( !empty(\Auth::user())){
            
            if ( $id != 'fantasma'){
                $usuario = $this->usuario->where('id', '=', $id)->first();
                
                if( isset($usuario->funcionario_id) ){
                    
                    $base64 = $this->funcionario->getAvatarPequenoBase( $usuario->funcionario_id );

                    if ( !empty($base64) )
                    {
                        return ImagemController::getResponse($base64);
                    }
            
                    return null;   
                }
            }
            
            return ImagemController::getResponse('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');

        }
        abort(401, 'Usuário não logado.');

    } 

    public function getAvatarGrande( $id )
    {
       

        if ( !empty(\Auth::user())){
            if ( $id != 'fantasma'){

                $usuario = $this->usuario->where('id', '=', $id)->first();
                if( isset($usuario->funcionario_id) ){
                    
                    $base64 = $this->funcionario->getAvatarGrandeBase( $usuario->funcionario_id );

                    if ( !empty($base64) )
                    {
                        return ImagemController::getResponse($base64);
                    }
            
                    return null;
                
                }        
            }
                return ImagemController::getResponse('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');
                
        }
        abort(401, 'Usuário não logado.');

    } 

 

}

