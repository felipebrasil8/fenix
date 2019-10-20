<?php

namespace App\Http\Controllers\Monitoramento;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\ExcelController;
use App\Http\Requests\Monitoramento\MonitoramentoServidoresRequest;
use App\Models\Monitoramento\MonitoramentoServidores;
use App\Models\Monitoramento\MonitoramentoServidoresItens;
use App\Models\Monitoramento\MonitoramentoServidoresStatus;
use App\Models\Monitoramento\MonitoramentoServidoresItensHistoricos;
use App\Models\Monitoramento\MonitoramentoServidoresParadaProgramada;
use App\Services\MonitoramentoServidoresClientesService;
use App\Models\Monitoramento\MonitoramentoServidoresChamados;
use App\Models\Configuracao\Usuario;


class ServidorController extends Controller
{

    private $monitoramentoServidores;
    private $monitoramentoServidoresItens;
    private $monitoramentoServidoresStatus;
    private $entidade = 'Servidores';
    
    public function __construct( 
                                    MonitoramentoServidores $monitoramentoServidores,
                                         MonitoramentoServidoresItens $monitoramentoServidoresItens,
                                            MonitoramentoServidoresStatus $monitoramentoServidoresStatus)
    {
        $this->monitoramentoServidores = $monitoramentoServidores;
        $this->monitoramentoServidoresItens = $monitoramentoServidoresItens;
        $this->monitoramentoServidoresStatus = $monitoramentoServidoresStatus;
    }


    public function index()
    {
        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_VISUALIZAR' );

        $produtos = $this->monitoramentoServidores->getDistinctInfo( 'grupo' );

        $clientes = $this->monitoramentoServidores->getDistinctInfo( 'cliente' );
        
        $tipos = $this->monitoramentoServidores->getDistinctInfo( 'tipo' );
      
        $statusInstalacao = $this->monitoramentoServidores->getDistinctInfo( 'status' );

        $versao = $this->monitoramentoServidores->getDistinctInfo( 'versao' );

        $itens = $this->monitoramentoServidoresItens->getItensServidores();

        $statusServidores = $this->monitoramentoServidoresStatus->getStatusServidor();
      
        return view('monitoramento.servidores.index', [
            'ativo' => 'true', 
            'filtro' => false,
            'migalhas' => $this->migalhaDePao( 'INDEX' ),
            'itens' => $itens,
            'produtos' => $produtos,
            'clientes' => $clientes,
            'statusServidores' => $statusServidores,
            'statusInstalacao' => $statusInstalacao,
            'versao' => $versao,
            'tipos' => $tipos,
            'can' => [ 
                    'exportar'  => \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_EXPORTAR' ),
                    'editar'    => \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_EDITAR' ),
                ]
            
        ] );
    }

    public function search(Request $request)
    {
        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_VISUALIZAR' );
 
        $servidores = $this->monitoramentoServidores->tableMonitoramentoServidores($request);

        return [
           'servidores' => $servidores
        ];


    }

    public function exportar( Request $request, ExcelController $excel, $type ) {    

        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_EXPORTAR' );
        
        $dados = $this->monitoramentoServidores->exportarMonitoramentoServidores($request);        

        $titulo_excel = array("Data", "Hora", "Cliente", "Produto", "Projeto", "Status Instalação", "Tipo", "IP", "DNS", "Versão", "Status servidor", "Cpu", "Discos", "Memória", "nº ativo", "nº série");

        
        foreach ($dados as $dado) {

            $dado->__set( 'hora', $this->date()->formataHoraExcel($dado['data']) );
            $dado->__set( 'data', $this->date()->formataDataExcel($dado['data']) );

            $cpu = '-';
            $numero_ativo = '-';
            $numero_serie = '-';
            $disco = '-';
            $memoria = '-';

            if( !is_null($dado->configuracao)){
           
                $configuracao = json_decode($dado->configuracao);
                $disco = '-';
                $i = 0;
                foreach ( $configuracao->discos as $key => $value) {
                    if ( $i == 0) {
                        $disco = $key.' = '.$value;
                    }else{
                        $disco = $disco.', '.$key.' = '.$value;
                    }
                
                    $i++;
                
                }

                $memoria = '-';
                $i = 0;
                foreach ( $configuracao->memoria as $key => $value) {
                    $valor = (is_null($value) ? 0: $value );
                    if ( $i == 0) {
                        $memoria = $key.' = '.$valor;
                    }else{
                        $memoria = $memoria.', '.$key.' = '.$valor;
                    }
                
                    $i++;
                
                }


                $cpu = !empty($configuracao->cpu)? $configuracao->cpu : '-' ;
                $numero_ativo = !empty($configuracao->servico->numero_ativo)? $configuracao->servico->numero_ativo : '-' ;
                $numero_serie = !empty($configuracao->servico->numero_serie)? $configuracao->servico->numero_serie : '-' ;
                
            }

            $dado->__set( 'cpu', $cpu );
            $dado->__set( 'discos', $disco );
            $dado->__set( 'memoria', $memoria );

            $dado->__set( 'ativo', $numero_ativo );
            $dado->__set( 'serie', $numero_serie );

            $dado->__unset( 'configuracao' );

        }

        $formato = [
                'A' => "dd/mm/yyyy", 
                'B' => "hh:mm:ss"
            ];

        return $excel->downloadExcel($type, $dados->toArray(), 'monitoramento_servidores_', $titulo_excel, $formato);       

    }


    public function edit( $id ) {

        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_EDITAR' );

        $servidor = MonitoramentoServidores::select('id', 'porta_api', 'executa_ping', 'executa_porta', 'cliente', 'id_projeto', 'grupo', 'tipo')->where('id', $id)->first();

        if( empty( $servidor ) ) {

            return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoEditar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
        
        }        

        return view('monitoramento.servidores.editar', [
            'servidor' => $servidor, 
            'can' => [ 
                'cadastrar' => \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_EDITAR' )
            ],
            'migalhas' => $this->migalhaDePao()
        ]);
                
    }

    public function update( Request $request, $id ){

        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_EDITAR' );

        $servidor = MonitoramentoServidores::select('id')->where('id', $id)->first();

        if( empty( $servidor ) )
        {
            return \Response::json(['errors' => ['errors' => [$this->errors()->msgUpdate( $this->entidade )] ] ], 500);
        } 

        try{

            MonitoramentoServidores::where('id', $id)->update([
                'porta_api'=> $request->porta_api, 
                'executa_porta' => $request->executa_porta,
                'executa_ping' => $request->executa_ping
            ]);
        
        }
        catch(\Exception $e)
        {
            return \Response::json(['errors' => ['errors' => [$this->errors()->msgUpdate( $this->entidade )] ] ], 404);
        }

        return ['mensagem' => $this->success()->msgUpdate( $this->entidade ), 'id' => $id ];

    }

    /**
     * Visualizar dados do servidor
     */
    public function show( $id, $aba='dados' )
    {
        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_VISUALIZAR' );

        $abas = $this->setAbas();
        $servidor = MonitoramentoServidores::
            select(
                'monitoramento_servidores.id', 
                'monitoramento_servidores.tipo',
                'monitoramento_servidores.cliente', 
                'monitoramento_servidores.id_projeto',
                'monitoramento_servidores.status',
                'monitoramento_servidores.plano',
                'monitoramento_servidores.plano_tipo',
                'monitoramento_servidores.grupo',
                'monitoramento_servidores_status.cor',
                'monitoramento_servidores_status.icone',
                'monitoramento_servidores_status.nome'
            )
            ->leftJoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', 'monitoramento_servidores.monitoramento_servidores_status_id')
            ->where('monitoramento_servidores.id', $id)->first();

        if ( !array_key_exists($aba, $abas ) || empty( $servidor ) ){

            return $this->paginaNaoEncontrada( [
                'titulo' => $this->entidade, 
                'descricao' => $this->errors()->descricaoVisualizar( $this->entidade ), 
                'mensagem' => $this->errors()->mensagem( $this->entidade ), 
                'migalhas' => $this->migalhaDePao()
            ]);
        
        }

        return view('monitoramento.servidores.visualizar', [
            'servidor' => $servidor, 
            'aba' => $aba,
            'abas' => $abas,
            'can' => [ 
                'servidor_editar' => \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_EDITAR' ),
            ],
            'migalhas' => $this->migalhaDePao()
        ]);
        
    }


    private function setAbas(){

        $abas = [];
        
        if( \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_ABA_DADOS_SERVIDORES_VISUALIZAR' ) )
        {
            $abas['dados'] = ['nome' => "Dados", 'icone'=> 'fa-server' ];
        }

        if( \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_ABA_CONFIGURACAO_SERVIDORES_VISUALIZAR' ) )
        {
            $abas['configuracoes'] = [ 'nome' => "Configurações", 'icone'=> 'fa-cog' ];
        }

        if( \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_ABA_HISTORICO_ALERTA_SERVIDORES_VISUALIZAR' ) )
        {
            $abas['alertas'] = [ 'nome' => "Alertas", 'icone'=> 'fa-exclamation-triangle' ];
        }

        if( \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_ABA_ITENS_MONITORADOS_SERVIDORES_VISUALIZAR' ) )
        {
            $abas['itens_monitorados'] = [ 'nome' => "Itens monitorados", 'icone'=> 'fa-list' ];
        }

        if( \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_ABA_VINCULO_CHAMADOS_SERVIDORES_VISUALIZAR' ) )
        {
            $abas['chamados_vinculados'] = [ 'nome' => "Chamados vinculados", 'icone'=> 'fa-reply-all' ];
        }

        if( \Auth::user()->can( 'MONITORAMENTO_SERVIDORES_ABA_PARADAS_PROGRAMADAS_SERVIDORES_VISUALIZAR' ) )
        {
            $abas['paradas_programadas'] = [ 'nome' => "Paradas programadas", 'icone'=> 'fa-clock-o' ];
        }

        return $abas;

    }

    public function abaDados( $id )
    {
        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_ABA_DADOS_SERVIDORES_VISUALIZAR' );

        $monitoramentoServidores = new MonitoramentoServidores();

        return ['servidor' => $monitoramentoServidores->abaDados( $id )];
    }

    public function abaConfiguracao( $id )
    {
        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_ABA_CONFIGURACAO_SERVIDORES_VISUALIZAR' );

        $monitoramentoServidores = new MonitoramentoServidores();

        return ['servidor' => $monitoramentoServidores->abaConfiguracao( $id )];
    }

    public function abaAlerta( $id, Request $request )
    {
        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_ABA_HISTORICO_ALERTA_SERVIDORES_VISUALIZAR' );

        $monitoramentoHistoricos = new MonitoramentoServidoresItensHistoricos();

        $monitoramentoHistoricos = $monitoramentoHistoricos->tableMonitoramentoServidoresItensHistoricos($id, $request);

        return [
            'servidor_historico' => $monitoramentoHistoricos
        ];
    }

    public function abaAlertaDados( $id )
    {
        $status = MonitoramentoServidoresStatus::select('id', 'nome')->orderBy('peso', 'desc')->get();

        $servidor = new MonitoramentoServidoresItens();
        $servidor->id = null;
        $servidor->nome = 'Servidor';

        $itens = $this->monitoramentoServidoresItens->getItensServidores($id);

        $itens->push( $servidor );

        return ['status' => $status,
                'itens' => $itens];
    }

    public function abaItensMonitorados( $id ){

        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_ABA_ITENS_MONITORADOS_SERVIDORES_VISUALIZAR' );

        $monitoramentoServidoresItens = new MonitoramentoServidoresItens();

        return [ 'itens' => $monitoramentoServidoresItens->abaItensMonitorados( $id ) ];
    }


    public function abaVinculoChamados( $id, Request $request ){

        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_ABA_VINCULO_CHAMADOS_SERVIDORES_VISUALIZAR' );

        $monitoramentoServidoresChamados = new MonitoramentoServidoresChamados();

        return [ 'chamados' => $monitoramentoServidoresChamados->abaVinculoChamados( $id, $request ) ];
    }

    public function abaVinculoChamadosDados( $id ){
        
        $monitoramentoServidoresChamados = new MonitoramentoServidoresChamados();
       
        $usuariosInclusao = MonitoramentoServidoresChamados::
                    select('usuarios.nome', 'monitoramento_servidores_chamados.usuario_inclusao_id as id')
                    ->where( 'monitoramento_servidores_chamados.monitoramento_servidores_id' ,$id)
                    ->leftJoin('usuarios', 'usuarios.id', '=', 'monitoramento_servidores_chamados.usuario_inclusao_id')
                    ->distinct('usuarios.nome')
                    ->distinct('monitoramento_servidores_chamados.usuario_inclusao_id')
                    ->orderBy('usuarios.nome')
                    ->get()
                    ;

        $usuariosAlteracao = MonitoramentoServidoresChamados::
                    select('usuarios.nome', 'monitoramento_servidores_chamados.usuario_alteracao_id as id')
                    ->where( 'monitoramento_servidores_chamados.monitoramento_servidores_id' ,$id)
                    ->whereNotNull('usuarios.nome')
                    ->leftJoin('usuarios', 'usuarios.id', '=', 'monitoramento_servidores_chamados.usuario_alteracao_id')
                    ->distinct('usuarios.nome')
                    ->distinct('monitoramento_servidores_chamados.usuario_alteracao_id')
                    ->orderBy('usuarios.nome')
                    ->get();

     
        $servidor = new MonitoramentoServidoresItens();
        $servidor->id = null;
        $servidor->nome = 'Servidor';

        $itens = $this->monitoramentoServidoresItens->getItensServidores($id);

        $itens->push( $servidor );

        return ['itens' => $itens,
                'usuariosInclusao' => $usuariosInclusao,
                'usuariosAlteracao' => $usuariosAlteracao 
            ];
    }

    public function abaParadaProgramada( $id, Request $request )
    {
        $this->autorizacao( 'MONITORAMENTO_SERVIDORES_ABA_PARADAS_PROGRAMADAS_SERVIDORES_VISUALIZAR' );

        $monitoramentoParada = new MonitoramentoServidoresParadaProgramada();

        $monitoramentoParada = $monitoramentoParada->tableMonitoramentoServidoresParadaProgramada($id, $request);

        return [
            'servidor_parada' => $monitoramentoParada
        ];
    }

    public function abaParadaDados( $id )
    {
        $usuariosParada = MonitoramentoServidoresParadaProgramada::select('usuario_inclusao.id', 'usuario_inclusao.nome')
                    ->where('monitoramento_servidores_parada_programada.monitoramento_servidores_id', $id)
                    ->distinct('monitoramento_servidores_parada_programada.usuario_inclusao_id')
                    ->leftjoin('usuarios AS usuario_inclusao', 'monitoramento_servidores_parada_programada.usuario_inclusao_id', '=', 'usuario_inclusao.id')                    
                    ->orderBy('usuario_inclusao.nome')
                    ->get();

        return ['usuarios' => $usuariosParada];
    }

}
