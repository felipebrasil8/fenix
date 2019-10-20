<?php

namespace App\Http\Controllers\Monitoramento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Configuracao\Sistema\Parametro;
use App\Models\Monitoramento\MonitoramentoServidores;
use App\Models\Monitoramento\LogMonitoramentoServidores;
use App\Models\Monitoramento\MonitoramentoServidoresColetas;


class ServicoController extends Controller
{

	private $monitoramentoServidores;
    private $filtro;
    private $filtroDe;
    private $filtroAte;

    public function __construct( 
                                    MonitoramentoServidores $monitoramentoServidores 
                                )
    {
        $this->monitoramentoServidores = $monitoramentoServidores;
    }
    
    public function index()
    {
    	$this->autorizacao( 'MONITORAMENTO_SERVICO_VISUALIZAR' );

        $parametros = Parametro::getParametrosMonitoramento();

        $tempo = Parametro::select('valor_numero')
                ->where( [['ativo', '=', true], ['nome', '=', 'TEMPO_TIMEOUT_TELA_SERVICO']])
                ->get()
                ->first()->valor_numero;
    
        return view('monitoramento.servico.index', [
            'parametros' => $parametros,            
            'migalhas' => $this->migalhaDePao( 'INDEX' ),
            'timeout' => $tempo
        ] );

        
    }

    /**
     * [setStatus Alterar o parametro do servico, ativado ou desativado]
     * @param [boolean] $status [status atual do parametro]
     */
    public function setStatus($status){

        $this->autorizacao( 'MONITORAMENTO_SERVICO_VISUALIZAR' );
        
        Parametro::where('nome', '=', 'MONITORAMENTO_SERVICO_ATIVO')->update(
            [
                'valor_booleano' => $status,
                'usuario_alteracao_id' => \Auth::user()->id
                
            ]);
        
        return $status;

    }

    /**
     * [getServico Retono do Ajax da busca de serviço.]
     * @param  Request $request [Filtro da tela (data)]
     * @return [json]           [informações que são exibidas na tela. ]
     */
    public function getServico( Request $request ){

            
        $this->autorizacao( 'MONITORAMENTO_SERVICO_VISUALIZAR' );
        $this->setPeriodoFiltro($request);
        
        $parametro = Parametro::select('parametros.valor_booleano', 'usuarios.nome')
                                ->selectRaw('( TO_CHAR(parametros.updated_at , \'dd/mm/yyyy hh24:mi:ss\') ) as data')
                                ->leftjoin('usuarios', 'parametros.usuario_alteracao_id', '=', 'usuarios.id')
                                ->where('parametros.nome', '=', 'MONITORAMENTO_SERVICO_ATIVO')
                                ->first();


        $duracao_ultima = LogMonitoramentoServidores::select('duracao')->orderBy('id', 'desc')->first()->duracao;
        
        $servidores = MonitoramentoServidoresColetas::
                                    whereRaw( $this->dateFilter('monitoramento_servidores_coletas.created_at') )
                                    ->distinct('monitoramento_servidores_coletas.monitoramento_servidores_id')
                                    ->count('monitoramento_servidores_coletas.monitoramento_servidores_id');
       
        $duracao_media_coleta = MonitoramentoServidoresColetas::
                                    whereRaw( $this->dateFilter('monitoramento_servidores_coletas.created_at') )
                                    ->avg('tempo_de_resposta');

        $duracao_media_servico = LogMonitoramentoServidores::
                                whereRaw( $this->dateFilter('logs_monitoramento_servidores.inicio_coleta') )
                                ->avg('duracao');

        $coletas_falhas = MonitoramentoServidoresColetas::
                                where('monitoramento_servidores_status.identificador', 'FORA' )
                                ->whereRaw( $this->dateFilter('monitoramento_servidores_coletas.created_at') )
                                ->leftjoin('monitoramento_servidores_status', 'monitoramento_servidores_status.id', '=', 'monitoramento_servidores_coletas.monitoramento_servidores_status_id')
                                ->count();
        
        $coletas_totais = MonitoramentoServidoresColetas::
                    whereRaw( $this->dateFilter('monitoramento_servidores_coletas.created_at') )
                    ->count();
        
        $coletas_falhas_percent = 0;
        if ($coletas_totais != 0){
            $coletas_falhas_percent = (100 * $coletas_falhas)/$coletas_totais;
        }
  
        return [
            'info_servico' => [
                'status' => $parametro->valor_booleano, 
                'usuario' => $parametro->nome, 
                'data' => $parametro->data, 
                'servidores' => $servidores,
                'coletas_totais' => number_format($coletas_totais, 0, ',', '.'),
                'duracao_ultima' => number_format($duracao_ultima, 2, ',', '.'),
                'coletas_falhas' => number_format($coletas_falhas, 0, ',', '.'),
                'coletas_falhas_percent' => number_format($coletas_falhas_percent, 0, ',', '.'),
                'duracao_media_servico' => number_format($duracao_media_servico, 2, ',', '.'),
                'duracao_media_coleta' => number_format($duracao_media_coleta, 2, ',', '.')
            ]
        ];

    }

 
    /**
     * [dateFilter Retorna o sql para o WHERE resposanvel por filtrar a data.]
     * @param  [string] $column [Coluna de data da tabela que sera filtrada.]
     * @return [query]         [Sql com o where necessario para filtrar a data.]
     */
    private function dateFilter( $column )
    {
       
        if( $this->filtro == 'hoje' )
        {
            return $column."::DATE = CURRENT_DATE";
        }
        else if(  $this->filtro == 'customizado' )
        {
            if( isset( $this->filtroDe ) && isset( $this->filtroAte ) )
            {
                return "".$column."::DATE BETWEEN '".$this->filtroDe."'::DATE AND '".$this->filtroAte."'::DATE";
            }
        }

        return "FALSE";
    }

    /**
     * [setPeriodoFiltro Salva em variavel global as informações do filtro.]
     * @param [object] $request [ Filtro de data da tela]
     */
    private function setPeriodoFiltro( $request )
    {
        $this->filtro = $request->selected_date;
        $this->filtroDe = $request->selected_date_de;
        $this->filtroAte = $request->selected_date_ate;

    }



}
