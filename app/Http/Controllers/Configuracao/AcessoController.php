<?php

namespace App\Http\Controllers\Configuracao;

use App\Http\Controllers\Controller;

use App\Http\Requests\Configuracao\AcessoRequest;
use App\Models\Configuracao\Acesso;
use App\Models\Configuracao\AcessoPermissao;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class AcessoController extends Controller
{
    private $entidade = 'Acesso';
    private $acesso;
    private $acessoPermissao;

    public function __construct(
                                Acesso $acesso,
                                    AcessoPermissao $acessoPermissao
                                )
    {
        $this->acesso = $acesso;
        $this->acessoPermissao = $acessoPermissao;
    }
    
    public function index()
    {
        $this->autorizacao( 'CONFIGURACAO_ACESSO_VISUALIZAR' );

        return view('configuracao.acesso.index', [
            'migalhas' => $this->migalhaDePao( 'INDEX' ),
            'can' => [
                'status' => \Auth::user()->can( 'CONFIGURACAO_ACESSO_STATUS' ),
                'editar' => \Auth::user()->can( 'CONFIGURACAO_ACESSO_EDITAR' ),
                'copiar' => \Auth::user()->can( 'CONFIGURACAO_ACESSO_COPIAR' ),
                'cadastrar' => \Auth::user()->can( 'CONFIGURACAO_ACESSO_CADASTRAR' )
                ]
            ] );
	}

    public function search(Request $request)
    {
        $this->autorizacao('CONFIGURACAO_ACESSO_VISUALIZAR');
           
        $acessos = $this->acesso->getAcessos($request);

        return [
           'acessos' => $acessos
        ];
    }

    public function show( $id )
    {
        $this->autorizacao('CONFIGURACAO_ACESSO_VISUALIZAR');

        $acesso = $this->acesso->getAcesso($id);
        
        // if( empty( $acesso ) )
        // {
        //     return $this->paginaNaoEncontrada( [
        //         'titulo' => $this->entidade, 
        //         'descricao' => $this->errors()->descricaoVisualizar( $this->entidade ), 
        //         'mensagem' => $this->errors()->mensagem( $this->entidade ), 
        //         'migalhas' => $this->migalhaDePao()
        //     ]);
        // }
        // 
        if( !isset( $acesso ) )
        {
            return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
        }
       

        $permissoes = $this->acesso->listarPermissoesRecursivo();

        $acesso_permissao = $this->acessoPermissao->select('permissao_id as id')->where('acesso_id', '=', $id)->get()->pluck('id');

        return view( 'configuracao.acesso.visualizar', [
            'migalhas' => $this->migalhaDePao(),
            'acesso' => $acesso,
            'permissoes' => $permissoes,
            'acesso_permissao' => $acesso_permissao,
            'can' => [
                'editar' => \Auth::user()->can( 'CONFIGURACAO_ACESSO_EDITAR' ),
                'copiar' => \Auth::user()->can( 'CONFIGURACAO_ACESSO_COPIAR' ),
                ]
        ] );
    }


    public function create()
    {
        $this->autorizacao('CONFIGURACAO_ACESSO_CADASTRAR');

        $permissoes = $this->acesso->listarPermissoesRecursivo();
        
    	return view('configuracao.acesso.cadastrar', [
                'permissoes' => $permissoes, 
                'migalhas' => $this->migalhaDePao()
            ]);
    }


    public function store(AcessoRequest $request)
    {
        // Determina se foi uma copia
        $copia = (!empty($request->id)?true:false);

        if (!$copia)
            $this->autorizacao('CONFIGURACAO_ACESSO_CADASTRAR');
        else
            $this->autorizacao('CONFIGURACAO_ACESSO_COPIAR');
        
        DB::beginTransaction();

        try{

            $acesso = $this->acesso->insertGetId(['nome' => $this->formatString()->strToUpperCustom( $request->nome ), 'usuario_inclusao_id' => \Auth::user()->id]);

            foreach ($request->permissoes as $valor)
            {
                $this->acessoPermissao->insert([
                        'acesso_id' => $acesso,
                        'permissao_id' => $valor 
                    ]);                  
            }
        

            DB::commit();
        }
        catch( \Exception $e )
        {
            DB::rollback();
            return \Response::json(['errors' => ['errors' => [ $this->errors()->msgStore( $this->entidade ) ] ] ] ,404);

        }

        $msg = ( !$copia ? $this->success()->msgStore( $this->entidade ) : $this->success()->msgCopy( $this->entidade ) );

        return ['mensagem' => $msg, 'id' => $acesso];
    }

    public function edit( $id )
    {

        $this->autorizacao('CONFIGURACAO_ACESSO_EDITAR');
        $acesso = $this->acesso->select('id', 'nome')->where('id', '=', $id)->first();

         if( empty( $acesso ) )
        {
            return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
        }

        $permissoes = $this->acesso->listarPermissoesRecursivo();
        $acesso_permissao = $this->acessoPermissao->select('permissao_id as id')->where('acesso_id', '=', $id)->get()->pluck('id');
        
                

            
        return view( 'configuracao.acesso.editar', [ 
                'migalhas' => $this->migalhaDePao(), 
                'acesso' => $acesso,
                'permissoes' => $permissoes, 
                'acesso_permissao' => $acesso_permissao
                ]);      
 



    }

    public function update( AcessoRequest $request, $id )
    {        
        $this->autorizacao('CONFIGURACAO_ACESSO_EDITAR');

        DB::beginTransaction();
        try{

            $nome = $this->formatString()->strToUpperCustom($request->nome);
           
            $this->acesso->where('id', '=', $id)->update([
                'nome' => $nome,
                'usuario_alteracao_id' => \Auth::user()->id,
                ]);

            $this->acessoPermissao::
                where('acesso_id', '=', $id)
                ->delete();

            if( !empty($request->permissoes) )
            {
                foreach ($request->permissoes as $valor)
                {
                    $this->acessoPermissao->insert([
                            'acesso_id' => $id,
                            'permissao_id' => $valor 
                        ]);                  
                }
            }
            DB::commit();
        }
        catch( \Exception $e )
        {
            DB::rollback();
            return \Response::json(['errors' =>  ['errors' => [ $this->errors()->msgUpdate( $this->entidade ) ] ] ] ,404);
        }
        return ['status'=>true, 'mensagem' => $this->success()->msgUpdate( $this->entidade ), 'id' => $id ];
    }

    public function destroy( $id )
    {
        
        $this->autorizacao('CONFIGURACAO_ACESSO_STATUS');

        $acesso = $this->acesso::where('id', $id)->first();

        if( isset($acesso) )
        {
            $ativo = !$acesso->ativo;

            $this->acesso::
                    where('id', '=',$id)
                    ->update([
                    'usuario_alteracao_id' => \Auth::user()->id,
                    'ativo' => $ativo,
                    ]);

            return \Response::json($id, 200);
        }else{
            return \Response::json(['errors' =>  ['errors' => [ $this->errors()->msgDestroy( $this->entidade ) ]]] ,404);
            
        }

    }

    public function copy( $id )
    {
        $this->autorizacao('CONFIGURACAO_ACESSO_COPIAR');

        $acesso = $this->acesso->getAcesso($id);
               
         if( empty( $acesso ) )
        {
            return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade,
                'descricao' => $this->errors()->descricaoCopiar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
        }

        $permissoes = $this->acesso->listarPermissoesRecursivo();

        $acesso_permissao = $this->acessoPermissao->select('permissao_id as id')->where('acesso_id', '=', $id)->get()->pluck('id');

        return view( 'configuracao.acesso.copiar', [
            'migalhas' => $this->migalhaDePao(),
            'acesso' => $acesso,
            'permissoes' => $permissoes,
            'acesso_permissao' => $acesso_permissao
        ] );
    }
    
}
