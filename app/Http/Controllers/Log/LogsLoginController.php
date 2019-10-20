<?php

namespace App\Http\Controllers\Log;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

use App\Repositories\Log\LogsLoginRepositoryInterface;
use App\Repositories\Configuracao\PerfilRepositoryInterface;

use App\Models\Configuracao\Perfil;
use App\Models\Core\Login;

use App\Http\Controllers\Core\ExcelController;

use Carbon\Carbon;
use DB;
use Session;
use Excel;

class LogsLoginController extends Controller
{	
	protected $repository;
    protected $perfilRepository;
    private $entidade = 'Login';
    private $strAutorizacaoModulo = 'LOG_LOGIN_';

    /**
     * Select da tabela para exportacao de excel(csv, xlsx)
    */
    private $select_excel = "to_char(created_at, 'dd/mm/YYYY') AS data, to_char(created_at, 'HH24:MI:SS') AS hora, usuario AS usuário, perfil AS perfil, ip, tipo, mensagem, credencial";

    /**
     * Titulos excel
     * Campos que serão atribuidos ao excel
     */
    private $titulo_excel = array( 'Data', 'Hora', 'Usuário', 'Perfil', 'IP', 'Tipo', 'Mensagem', 'Credencial' );


	public function __construct( 
        LogsLoginRepositoryInterface $repository,
            PerfilRepositoryInterface $perfilRepository,
                Errors $errors,
                    Success $success) {
        $this->repository = $repository;
        $this->perfilRepository = $perfilRepository;
        $this->errors = $errors;
        $this->success = $success;
    }

    public function index(  ) {

        $this->autorizacao($this->strAutorizacaoModulo.'VISUALIZAR');

    	try{

            $logslogin = $this->logsLoginClass();
            $perfis = $this->perfilClass();
            $tipos = ['FALHA', 'LOGIN', 'LOGOUT'];            
                        
        }catch(\Exception $e){

            return view( 'erros.naoEncontrado', [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors->mensagem( $this->entidade, $e->getMessage() ), 
                'migalhas' => $this->migalhaDePao()
            ] );
        }
        
        return view( 'log.acessos.index', [
            'logs' => $logslogin,
            'perfis' => $perfis,
            'tipos' => $tipos,
            'ativo'=>'true', 
            'filtro' => false, 
            'migalhas' => $this->migalhaDePao( 'INDEX' ) 
        ] );
    }

    public function search( ) {

        $this->autorizacao($this->strAutorizacaoModulo.'VISUALIZAR');

        try{

            $string = new Str;
            $de = input::get('de');            
            $ate = input::get('ate');
            $limite = input::get('limite');
            $ip = input::get('ip');            
            $tipo = input::get('tipo');
            $perfil = input::get('perfil');
            $nome = $string->upper( Input::get('usuario') );            
            $order = input::get('ordem');            

            $logs = $this->repository->paginacao(
                $limite, 
                input::get('to'), 
                [ 
                    [\DB::raw("sem_acento(\"usuario\")"), 'LIKE', \DB::raw("sem_acento('%".$nome."%')") ]
                    ,[\DB::raw("\"ip\""), 'LIKE', \DB::raw("'%".$ip."%'") ]                    
                    ,[\DB::raw("\"tipo\""), 'LIKE', \DB::raw("'%".$tipo."%'") ]
                    ,[\DB::raw("\"perfil\""), 'LIKE', \DB::raw("'%".$perfil."%'") ]
                    ,[\DB::raw("date(created_at)"), '>=', \DB::raw("date('".Carbon::createFromFormat('d/m/Y', $de)->format('Y-m-d')."')") ]
                    ,[\DB::raw("date(created_at)"), '<=', \DB::raw("date('".Carbon::createFromFormat('d/m/Y', $ate)->format('Y-m-d')."')") ]
                ],
                input::get('coluna')
                ,
                $order,
                [
                '*',
                \DB::raw("to_char(created_at, 'dd/mm/YYYY HH24:MI:SS') AS data")] 
                );                

            foreach ($logs as $log) {

                $log->__set('credencial', json_decode($log->credencial)->usuario );
                if( $log->tipo == 'FALHA' ){
                    $log->__set('mensagem', $log->tipo.' - '.$log->mensagem );
                }
            }
            
        }catch(\Exception $e){

            return \Response::json( ['errors' => $this->errors->msgSearch()] ,404);
        }

        return [
            'logs' => $logs
        ];
    }

    private function logsLoginClass(){

        try{

            $logslogin = $this->repository->scopeQuery(function($query){

                return $query->orderBy('created_at','desc'); 

            })->all();

        }catch(\Exception $e){
            
            throw new \Exception("Error:  logsLoginClass", 1);
            return ;            
        }       

        return $logslogin;
    }

    private function perfilClass(){
        
        try{

            $perfis = $this->perfilRepository->all('nome');
            $perfis_array = array();
            foreach ($perfis as $perfil) {
                
                array_push($perfis_array, $perfil->nome);
            }


        }catch(\Exception $e){
            
            $perfis_array = false;
        }       

        return $perfis_array;
    }
    
    public function downloadExcel( Request $request, ExcelController $excel, $type ) {

        $string = new Str;
       
        $query = Login::query();    

        $query = $query->selectRaw( $this->select_excel );

        if (Input::has('usuario')) {                
            $query = $query->whereRaw( "sem_acento(\"usuario\") LIKE sem_acento('%".$string->upper($request->usuario)."%')" );
        }

        if (Input::has('ip')) {            
            $query = $query->whereRaw( "\"ip\" LIKE '%".$request->ip."%'" );
        }

        if (Input::has('tipo')) {            
            $query = $query->whereRaw( "\"tipo\" LIKE '%".$request->tipo."%'" );
        }

        if (Input::has('perfil')) {            
            $query = $query->whereRaw( "\"perfil\" LIKE '%".$request->perfil."%'" );
        }

        if (Input::has('de')) {
            $query = $query->whereRaw( "date(created_at) >= ". ( \DB::raw( "date('".Carbon::createFromFormat('d/m/Y', $request->de)->format('Y-m-d') ) ) ."')" );
        }

        if (Input::has('ate')) {
            $query = $query->whereRaw( "date(created_at) <= ". ( \DB::raw( "date('".Carbon::createFromFormat('d/m/Y', $request->ate)->format('Y-m-d') ) ) ."')" );
        }

        $query = $query->orderByRaw('data desc, hora desc'); 

        $dados = $query->get();

        foreach ($dados as $dado) {            
            $dado->__set('credencial', json_decode($dado['credencial'])->usuario );
        }

        return $excel->downloadExcel($type, $dados->toArray(), 'log_acessos_', $this->titulo_excel);

    }
}
