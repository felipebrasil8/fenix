<?php

namespace App\Http\Controllers\Configuracao;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Http\Requests\Configuracao\PerfilRequest;

use Illuminate\Support\Facades\DB;

use App\Models\Configuracao\Acesso;
use App\Models\Configuracao\AcessoPerfil;
use App\Models\Configuracao\Perfil;

use App\Util\ValidateIpAddress;

use Illuminate\Http\Request;


class PerfilController extends Controller
{
    private $entidade = 'Perfil';
    private $entidadeAcesso = 'Acessos de Perfil';
    private $acessos;
    private $perfis;
    private $acessoPerfil;
    private $validateIpAddress;


    public function __construct(
                                Acesso $acessos,
                                Perfil $perfis,
                                AcessoPerfil $acessoPerfil
                            )
    {

        $this->acessos = $acessos;
        $this->perfis = $perfis;
        $this->acessoPerfil = $acessoPerfil;
        $this->validateIpAddress = new ValidateIpAddress();

    }

	public function index()
	{
        $this->autorizacao('CONFIGURACAO_PERFIL_VISUALIZAR');

        $acessos = $this->acessos->getAcessosAtivos();
		
        return view('configuracao.perfil.index', [
            'acessos' => $acessos,
            'migalhas' => $this->migalhaDePao( 'INDEX' ), 
            'can' => [ 
                    'status'    => \Auth::user()->can( 'CONFIGURACAO_PERFIL_STATUS' ),
                    'editar'    => \Auth::user()->can( 'CONFIGURACAO_PERFIL_EDITAR' ),
                    'cadastrar' => \Auth::user()->can( 'CONFIGURACAO_PERFIL_CADASTRAR' ),
                    'visualizar' => \Auth::user()->can( 'CONFIGURACAO_PERFIL_VISUALIZAR' ),
                    'copiar' => \Auth::user()->can( 'CONFIGURACAO_PERFIL_COPIAR' ),
                ],
        ]);
	}

    /**
     * Pesquisa perfil por id
     */
    public function show( $id )
    {
        $this->autorizacao('CONFIGURACAO_PERFIL_VISUALIZAR');

        $perfil = $this->perfis->getPerfil($id);

        if(!isset($perfil)) {

              return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
            
        }


        $acessos = $this->acessos->getAcessoTree( $this->acessos->getAcessosSelecinado($id) );

        $acesso_perfil = $this->acessoPerfil->where('perfil_id', '=', $id)->select('acesso_id')->get()->pluck('acesso_id');


        return view('configuracao.perfil.visualizar', [
            'dados' => [
                   'perfil' => $perfil,
                   'acessos' => $acessos,
                   'acesso_perfil' => $acesso_perfil
               ],
            'can' => [ 
                    'editar'    => \Auth::user()->can( 'CONFIGURACAO_PERFIL_EDITAR' ),
                    'copiar' => \Auth::user()->can( 'CONFIGURACAO_PERFIL_COPIAR' ),
                ],
            'migalhas' => $this->migalhaDePao()
        ]);
        
    }

    /**
     * metodo de busca
     */
    public function search(Request $request)
    {
        $this->autorizacao('CONFIGURACAO_PERFIL_VISUALIZAR');

        return [
            'perfis' => $this->perfis->getPerfis($request)

        ];
       
    }

	/**
	 * retorna a View() para cadastrar Perfil
	 */
    public function create()
    {
        $this->autorizacao('CONFIGURACAO_PERFIL_CADASTRAR');

        try{

        	$acessos = $this->acessos->getAcessoTree( $this->acessos->getAcessosAtivos() );

        }catch(\Exception $e){

            return view( 'erros.naoEncontrado', 
                [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoCadastrar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()] );

        }

    	return view('configuracao.perfil.cadastrar', [
            'acessos' => $acessos, 
            'migalhas' => $this->migalhaDePao(),
            'can' => [ 
                'cadastrar' => \Auth::user()->can( 'CONFIGURACAO_PERFIL_CADASTRAR' )
            ],
        ]);
    }

    public function store(PerfilRequest $request)

    {
        if (!$request->copia)
            $this->autorizacao('CONFIGURACAO_PERFIL_CADASTRAR');
        else
            $this->autorizacao('CONFIGURACAO_PERFIL_COPIAR'); 

        
        if( $this->validaRedes($request->configuracao_de_rede) )
        {
            return \Response::json(['errors' => ['errors' => [$this->validateIpAddress->getMensagem()] ] ] ,404);
        }
        
        // Determina se foi uma copia
        $copia = (!empty($request->id)?true:false);

        DB::beginTransaction();
        try
        {
            // Caso seja cópia, percorre os acessos do perfil anterior
            if ( $copia && $request->id != '' ){
                
                $perfil = $this->perfis->getPerfil($request->id);

                $newPerfil = $this->perfis->insertGetId([
                    'nome' => $this->formatString()->strToUpperCustom( $request->nome ),
                    'usuario_inclusao_id' => \Auth::user()->id,
                    'todos_dias' => $perfil->todos_dias,
                    'horario_inicial' => $perfil->horario_inicial,
                    'horario_final' => $perfil->horario_final,
                    'configuracao_de_rede'=> $perfil->configuracao_de_rede

                ]);
                
                $acesso_perfil = $this->acessoPerfil->where('perfil_id', '=', $request->id)->select('acesso_id')->get();

                foreach ($acesso_perfil as $valor)
                {
                    $this->acessoPerfil->insert([
                            'acesso_id' => $valor->acesso_id,
                            'perfil_id' => $newPerfil 
                        ]);                  
                }

                $perfilId = $perfil->id;
            }

            // Caso seja cadastro
            if( !$copia && !empty($request->acessos) )
            {
                $perfilId = $this->perfis->insertGetId([
                    'nome' => $this->formatString()->strToUpperCustom( $request->nome ), 
                    'usuario_inclusao_id' => \Auth::user()->id,
                    'todos_dias' => $request->todos_dias,
                    'horario_inicial' => $request->horario_inicial,
                    'horario_final' => $request->horario_final,
                    'configuracao_de_rede' => $request->configuracao_de_rede,
                ]);

    			foreach ($request->acessos as $valor)
    			{
                    $this->acessoPerfil->insert([
                        'acesso_id' => $valor,
                        'perfil_id' => $perfilId 
                    ]);                
    			}
    		}

            DB::commit();

        }catch(\Exception $e){
            DB::rollback();
            return $copia ? 
                \Response::json(['errors' => ['errors' =>[ $this->errors()->msgCopia( $this->entidade ) ] ] ] ,404) : 
                \Response::json(['errors' => ['errors' =>[ $this->errors()->msgStore( $this->entidade ) ] ] ] ,404) ;
        }

        return ['mensagem' => 
            ( $copia ? 
                $this->success()->msgCopy( $this->entidade ) : 
                $this->success()->msgStore( $this->entidade ) 
            ), 'id' => $perfilId ];
    }

    /**
     * Retorna dados do perfil para edição
     */
    public function edit( $id )
    {
        $this->autorizacao('CONFIGURACAO_PERFIL_EDITAR');

        $perfil = $this->perfis->getPerfil($id);

        if(!isset($perfil)) {
    		 return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
        }

        $acessos = $this->acessos->getAcessoTree( $this->acessos->getAcessosAtivos() );

        $acesso_perfil = $this->acessoPerfil->where('perfil_id', '=', $id)->select('acesso_id')->get()->pluck('acesso_id');

        
        return view('configuracao.perfil.editar', [
           'perfil' => $perfil,
           'acessos' => $acessos,
           'acesso_perfil' => $acesso_perfil,
            'can' => [ 
                    'editar'    => \Auth::user()->can( 'CONFIGURACAO_PERFIL_EDITAR' ),
                ],
            'migalhas' => $this->migalhaDePao()
        ]);

    }

    /**
     * Atualiza dados do Perfil
     */
    public function update( PerfilRequest $request, $id )
    {
        $this->autorizacao('CONFIGURACAO_PERFIL_EDITAR');
        
        if( $this->validaRedes($request->configuracao_de_rede) )
        {
            return \Response::json(['errors' => ['errors' => [$this->validateIpAddress->getMensagem()] ] ] ,404);
        }

        try{

            \DB::beginTransaction();

            $this->perfis->where('id', '=', $id)->update([
                'nome' => $this->formatString()->strToUpperCustom( $request->nome ), 
                'usuario_alteracao_id' => \Auth::user()->id,
                'todos_dias' => $request->todos_dias,
                'horario_inicial' => $request->horario_inicial,
                'horario_final' => $request->horario_final,
                'configuracao_de_rede' => $request->configuracao_de_rede,
                ]);

            $this->acessoPerfil::
                where('perfil_id', '=', $id)
                ->delete();

            if( !empty($request->acessos) )
            {
                foreach ($request->acessos as $valor)
                {
                    $this->acessoPerfil->insert([
                            'perfil_id' => $id,
                            'acesso_id' => $valor 
                        ]);                  
                }
            }
            
            \DB::commit();

        }catch(\Exception $e){

            \DB::rollback();
            return \Response::json(['errors' => ['errors' => [ $this->errors()->msgUpdate( $this->entidade.$e ) ] ] ] ,404) ;

        }

	    return ['mensagem' => $this->success()->msgUpdate( $this->entidade ), 'id' => $id ];
    }

    /**
     * Muda o Status
     */
    public function destroy($id )
    {
        $this->autorizacao('CONFIGURACAO_PERFIL_STATUS');
        $perfil = $this->perfis::where('id', $id)->first();

        if( isset($perfil) )
        {
            $ativo = !$perfil->ativo;

            $this->perfis->where('id', '=',$id)
                    ->update([
                    'usuario_alteracao_id' => \Auth::user()->id,
                    'ativo' => $ativo,
                    ]);

            return \Response::json($id, 200);
        }else{
            return \Response::json(['errors' =>  ['errors' => [ $this->errors()->msgDestroy( $this->entidade ) ]]] ,404);

        }
    }

    /**
     * retorna a View() para copiar um Perfil
     */
    public function copy( $id )
    {
        $this->autorizacao('CONFIGURACAO_PERFIL_COPIAR');

        $perfil = $this->perfis->getPerfil($id);

        if(!isset($perfil)) {
             return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoCopiar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
        }


        $acessos = $this->acessos->getAcessoTree( $this->acessos->getAcessosSelecinado($id) );

        $acesso_perfil = $this->acessoPerfil->where('perfil_id', '=', $id)->select('acesso_id')->get()->pluck('acesso_id');

        return view('configuracao.perfil.copiar', [
            'dados' => [
                   'perfil' => $perfil,
                   'acessos' => $acessos,
                   'acesso_perfil' => $acesso_perfil
               ],
            'can' => [ 
                    'editar'    => \Auth::user()->can( 'CONFIGURACAO_PERFIL_EDITAR' ),
                    'copiar' => \Auth::user()->can( 'CONFIGURACAO_PERFIL_COPIAR' ),
                ],
            'migalhas' => $this->migalhaDePao()
        ]);
                  
    }    

    /**
     * [validaRedes Valida se o IP bate com a mascara]
     * @param  [type] $configuracaoRede [description]
     * @return [type]                   [description]
     */
    private function validaRedes ( $configuracaoRede )
    {
        if( isset($configuracaoRede) && $configuracaoRede != '' )
        {
            if( $this->validateIpAddress->validaRedes( $configuracaoRede ) != '' )
            {
                return true;
            }
        }
    }










}