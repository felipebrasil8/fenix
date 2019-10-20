<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BaseDeConhecimento\PublicacaoDashboard;
use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\Configuracao\Sistema\Parametro;
use App\Http\Controllers\Core\ExcelController;
use Excel;


class DashboardController extends Controller
{
    public function __construct(PublicacaoDashboard $publicacaoDashboard, 
                                       Publicacao $publicacao
                                         
                                      )
    {
        $this->publicacaoDashboard = $publicacaoDashboard;
        $this->publicacoes =  $publicacao;
       
    }

    public function index(){
    	 $this->autorizacao( 'BASE_DASHBOARD_VISUALIZAR' );

         $tempo = Parametro::select('valor_numero')
                ->where( [['ativo', '=', true], ['nome', '=', 'TEMPO_TIMEOUT_DASHBOARD_BASE_PUBLICACAO']])
                ->get()
                ->first()->valor_numero;
        
        return view('base-de-conhecimento.dashboard', [
            'migalhas' => $this->migalhaDePao('false'),
            'timeout'  => $tempo
        ]);
		    
    }

    public function ajax_conteudo( Request $request ){
        $this->autorizacao( 'BASE_DASHBOARD_VISUALIZAR' );

        $this->publicacaoDashboard->setPeriodoFiltro($request);

    	return [
    		'total_publicacoes' => $this->publicacaoDashboard->getTotalPublicacoesPublicadas(),
    		'total_colaboracoes' => $this->publicacaoDashboard->getTotalColaboradoresPublicadas(),
            'total_acesso_periodo' => $this->publicacaoDashboard->getTotalAcessoPeriodo(),
            'total_pesquisa_periodo' => $this->publicacaoDashboard->getTotalPesquisaPeriodo(),
            'total_pesquisa_sem_resposta_periodo' => $this->publicacaoDashboard->getTotalPesquisaSemResultadoPeriodo(),
            'total_publicacoes_novas_periodo' => $this->publicacaoDashboard->getTotalNovasPublicacoesPeriodo(),
            'total_publicacoes_atualizadas_periodo' => $this->publicacaoDashboard->getTotalPublicacoesAtualizadasPeriodo(),
            'total_usuarios_acesso_periodo' => $this->publicacaoDashboard->getTotalAcessoUsuarioPeriodo(),
    		'total_mensagens_recebidas' => $this->publicacaoDashboard->getTotalMensagensRecebidasPeriodo(),
            'total_mensagens_recebidas_nao_respondidas' => $this->publicacaoDashboard->getTotalMensagensRecebidasNaoRespondidasPeriodo(),
    		'pesquisas_mais_realizadas_periodo' => $this->publicacaoDashboard->getPesquisasMaisRealizadasPeriodo(),
    		'pesquisas_por_departamento_periodo' => $this->publicacaoDashboard->getPesquisasPorDepartamentoPeriodo(),
    		'pesquisas_por_usuario_periodo' => $this->publicacaoDashboard->getPesquisasPorUsuarioPeriodo(),
    		'podio_colaborador' => $this->publicacaoDashboard->getPodioColaborador(),
    		'publicacoes_categoria' => $this->publicacaoDashboard->getPublicacoesCategoria(),
            'publicacoes_acessadas_periodo' => $this->publicacaoDashboard->getMaisAcessadasPeriodo(),
    		'buscas_sem_resultado' => $this->publicacaoDashboard->getBuscasSemResultado(),
            'exportar_dashboard' => \Auth::user()->can( 'BASE_DASHBOARD_EXPORTAR' ),
            'tratar_pesquisa' => \Auth::user()->can( 'BASE_DASHBOARD_TRATAR_PESQUISAS' ),
            'tratar_mensagem' => \Auth::user()->can( 'BASE_DASHBOARD_TRATAR_MENSAGENS' )
    	];
    }

    public function dashboardDownload(Request $request, ExcelController $excel, $type = 'xlsx'){
      
        $this->autorizacao( 'BASE_DASHBOARD_EXPORTAR' );

        $this->publicacaoDashboard->setPeriodoFiltro($request);
        $titulo_excel = $request->titulo_excel;
            
        if( $titulo_excel == 'Novas'){

            $arquivo = 'publicacoes_novas_no_periodo';
            $titulo_excel = array('Categoria','Publicações', 'Percentual');
            $formato = array(
                'C' => '0.00%'
            );
            $dados = $this->calculaPorcentagem($this->publicacaoDashboard->getTotalNovasPublicacoesCategoriaPeriodo());
            
        }
        else if( $titulo_excel == 'Atualizacao'){

            $arquivo = 'publicacoes_Atualizacao_no_periodo';
            $titulo_excel = array('Categoria','Publicações', 'Percentual');
            $formato = array(
                'C' => '0.00%'
            );
            $dados = $this->calculaPorcentagem($this->publicacaoDashboard->getTotalAtualizadasPublicacoesCategoriaPeriodo());
            
        }
        if( $titulo_excel == 'PesquisasMaisRealizadas'){

            $arquivo = 'pesquisas_mais_realizadas';
            $titulo_excel = array('Pesquisa', 'Quantidade', 'Percentual');
            
            $formato = array(
                'C' => '0.00%'
            );
            $dados = $this->calculaPorcentagem($this->publicacaoDashboard->getPesquisasMaisRealizadasPeriodo(false));
            
        } 
        else if( $titulo_excel == 'PesquisaDepartamento'){

            $arquivo = 'pesquisa_departamento';
            $titulo_excel = array('Departamento', 'Área',  'Quantidade', 'Percentual');
            $formato = array(
                'D' => '0.00%'
            );
            $dados = $this->calculaPorcentagemDepartamento($this->publicacaoDashboard->getPesquisasPorDepartamentoPeriodo(false));
            
        }
        else if( $titulo_excel == 'PesquisaUsuario'){

            $arquivo = 'pesquisa_usuario';
            $titulo_excel = array('Funcionário','Departamento', 'Área', 'Quantidade', 'Percentual');
            $formato = array(
                'E' => '0.00%'
            );
            $dados = $this->calculaPorcentagemUsuario($this->publicacaoDashboard->getPesquisasPorUsuarioPeriodo(false));
            
        }
        else if( $titulo_excel == 'PodioColaborador'){

            $arquivo = 'podio_colaborador';
            $titulo_excel = array('Funcionário', 'Departamento', 'Área', 'Quantidade', 'Percentual');
            $formato = array(
                'E' => '0.00%'
            );
            $dados = $this->calculaPorcentagemUsuario($this->publicacaoDashboard->getPodioColaborador(false));
            
        }
        else if( $titulo_excel == 'PublicacoesCategoria'){

            $arquivo = 'publicacoes_categoria';
            $titulo_excel = array('Categoria', 'Sub Categoria', 'Quantidade', 'Percentual');
            $formato = array(
                'D' => '0.00%'
            );
            $dados = $this->calculaPorcentagemCategoria($this->publicacaoDashboard->getPublicacoesCategoriaSubcategoria());
            
        }
        else if( $titulo_excel == 'PublicacoesAcessadasPeriodo'){

            $arquivo = 'publicacoes_acessadas_periodo';
            $titulo_excel = array('ID da publicação', 'Título da publicação', 'Categoria', 'Sub categoria', 'Quantidade', 'Percentual');
            $formato = array(
                'F' => '0.00%'
            );
            $dados = $this->calculaPorcentagemAcessadas($this->publicacaoDashboard->getMaisAcessadasPeriodoExcel());
            
        }

        return $excel->downloadExcel($type, $dados, $arquivo, $titulo_excel, $formato);        


    }

    private function calculaPorcentagem($collection){
        

        $total = $collection->sum('valor');
        $dados = array();
        if( $total > 0 ){
            $dados = array();   
            for ($i=0; $i < count($collection); $i++) { 
                 $perStatus = '0';
                 if ( $collection[$i]['valor'] != 0 ) {
                        $perStatus = $collection[$i]['valor']/$total;
                    }
                array_push( $dados, 
                    array( 'nome' => $collection[$i]['nome'], 
                               'total'=>  ''.$collection[$i]['valor'].'' , 
                                    'totalP'=>   $perStatus                                  
                            ) );
            }
            
        }

        return $dados;

    }

    private function calculaPorcentagemDepartamento($collection){
        

        $total = $collection->sum('valor');
        $dados = array();
        if( $total > 0 ){
            $dados = array();   
            for ($i=0; $i < count($collection); $i++) { 
                 $perStatus = '0';
                 if ( $collection[$i]['valor'] != 0 ) {
                        $perStatus = $collection[$i]['valor']/$total;
                    }
                array_push( $dados, 
                    array( 'nome' => $collection[$i]['nome'], 
                                'area' => $collection[$i]['area'], 
                                    'total'=>  ''.$collection[$i]['valor'].'' , 
                                        'totalP'=>   $perStatus                                  
                            ) );
            }
            
        }

        return $dados;

    }

    private function calculaPorcentagemUsuario($collection){
        

        $total = $collection->sum('valor');
        $dados = array();
        if( $total > 0 ){
            $dados = array();   
            for ($i=0; $i < count($collection); $i++) { 
                 $perStatus = '0';
                 if ( $collection[$i]['valor'] != 0 ) {
                        $perStatus = $collection[$i]['valor']/$total;
                    }
                array_push( $dados, 
                    array( 'nome' => $collection[$i]['nome'], 
                                'departamento' => $collection[$i]['departamento'], 
                                    'area' => $collection[$i]['area'], 
                                        'total'=>  ''.$collection[$i]['valor'].'' , 
                                            'totalP'=>   $perStatus                                  
                            ) );
            }
            
        }

        return $dados;

    }

    private function calculaPorcentagemCategoria($collection){
        

        $total = $collection->sum('valor');
        $dados = array();
        if( $total > 0 ){
            $dados = array();   
            for ($i=0; $i < count($collection); $i++) { 
                $perStatus = '0';
                
                if ( $collection[$i]['valor'] != 0 ) {
                    $perStatus = $collection[$i]['valor']/$total;
                }

                if ( $collection[$i]['valor'] > 0 ){
                    array_push( $dados, 
                        array( 
                                'categoria' => $collection[$i]['categoria'],
                                    'sub' => ($collection[$i]['sub'] == ''? '-': $collection[$i]['sub']), 
                                        'total'=>  ''.$collection[$i]['valor'].'' , 
                                            'totalP'=>   $perStatus                                  
                                ) );
                    
                }
            }
            
        }

        return $dados;

    }

    private function calculaPorcentagemAcessadas($collection){
        

        $total = $collection->sum('valor');
        $dados = array();
        if( $total > 0 ){
            $dados = array();   
            for ($i=0; $i < count($collection); $i++) { 
                 $perStatus = '0';
                 if ( $collection[$i]['valor'] != 0 ) {
                        $perStatus = $collection[$i]['valor']/$total;
                    }
                array_push( $dados, 
                    array( 'id' => $collection[$i]['id'], 
                                'titulo' => $collection[$i]['titulo'], 
                                    'categoria' => $collection[$i]['categoria'], 
                                        'sub' => ($collection[$i]['sub'] == ''? '-': $collection[$i]['sub']), 
                                            'total'=>  ''.$collection[$i]['valor'].'' , 
                                                'totalP'=>   $perStatus                                  
                            ) );
            }
            
        }

        return $dados;

    }

  

}



