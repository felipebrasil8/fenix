<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BaseDeConhecimento\Publicacao;
use App\Models\BaseDeConhecimento\HistoricoPesquisaPublicacaoView;
use App\Models\BaseDeConhecimento\DadosPublicacaoView;
use App\Models\BaseDeConhecimento\PublicacoesRecomendacoes;
use App\Models\BaseDeConhecimento\PublicacaoVisualizacao;
use App\Http\Controllers\Core\ExcelController;
use Excel;

class PublicacaoExportacaoController extends Controller
{
    private $publicacao;
    private $historicoPesquisaPublicacaoView;
    private $dadosPublicacaoView;
    private $publicacoesRecomendacoes;
    private $publicacaoVisualizacao;


    public function __construct(  Publicacao $publicacao, 
                                    HistoricoPesquisaPublicacaoView $historicoPesquisaPublicacaoView,
                                         DadosPublicacaoView $dadosPublicacaoView, 
                                            PublicacoesRecomendacoes $publicacoesRecomendacoes,
                                                PublicacaoVisualizacao $publicacaoVisualizacao)

    {
        $this->publicacao = $publicacao;
        $this->historicoPesquisaPublicacaoView = $historicoPesquisaPublicacaoView;
        $this->dadosPublicacaoView = $dadosPublicacaoView;
        $this->publicacoesRecomendacoes = $publicacoesRecomendacoes;
        $this->publicacaoVisualizacao = $publicacaoVisualizacao;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->autorizacao( 'BASE_EXPORTACOES' );        

        return view('base-de-conhecimento.exportacao', [
            'migalhas' => $this->migalhaDePao('false'),
            'can' => $this->getPermissaoUsuario()
        ]);        
    }

    private function getPermissaoUsuario(){
        return [
                 'publicacoes' => \Auth::user()->can( 'BASE_EXPORTACOES_PUBLICACOES' ),
                 'pesquisa' => \Auth::user()->can( 'BASE_EXPORTACOES_PESQUISAS' ),
                 'visualicoes' => \Auth::user()->can( 'BASE_EXPORTACOES_VISUALIZACOES' ),
                 'recomendacoes' => \Auth::user()->can( 'BASE_EXPORTACOES_RECOMENDACOES' )
                ];
    }



    public function downloadExcel(Request $request, ExcelController $excel, $type = 'xlsx'){
            
        $formato = array();

        if ( $request->titulo_excel == 'publicacoes'){
            $this->autorizacao( 'BASE_EXPORTACOES_PESQUISAS' );
            
            $arquivo = 'Dados_de_Publicacao';
            $titulo_excel = array(
                'ID',
                'Categoria',
                'Categoria pai',
                'Título',
                'Resumo',
                'Data de publicação',
                'Data da última atualização',
                'Data de revisão',
                'Data de desativação',
                'Colaboradores',
                'Tags',
                'Data de atualização',
                'Restrição de acesso',
                'Qtde. de publicações relacionadas',
                'Quantidade de favoritos',
                'Qtde. de acessos (total)',
                'Qtde. de acessos (últimos 30 dias)',
                'Qtde. de usuários que acessou (total)',
                'Qtde. de usuários que acessou (últimos 30 dias)',
                'Usuário inclusão',
                'Data inclusão',
                'Hora inclusão',
                'Usuário última alteração',            
                'Data da última alteração',
                'Hora da última alteração',
                'Quantidade de recomendações'
            );

            $dados = $this->getDadosPublicacaoView( );

            $formato = array( 
                'F' => 'dd/mm/yyyy',
                'G' => 'dd/mm/yyyy',
                'H' => 'dd/mm/yyyy',
                'I' => 'dd/mm/yyyy',
                'M' => '0',
                'N' => '0',
                'O' => '0',
                'P' => '0',
                'U' => 'dd/mm/yyyy',
                'V' => "hh:mm:ss", 
                'X' => 'dd/mm/yyyy',
                'Y' => "hh:mm:ss", 
            ); 
           

        }else if( $request->titulo_excel == 'pesquisa' ){
            $this->autorizacao( 'BASE_EXPORTACOES_PESQUISAS' );
           
            $arquivo = 'Historico_Pesquisa_Publicacao_';
            $titulo_excel = array(            
                'Data', 
                'Horário', 
                'Nome', 
                'Departamento', 
                'Área', 
                'Busca', 
                'Ip', 
                'Qtde. resultados',
                'Tratada', 
                'Data tratada', 
                'Hora tratada', 
                'Usuário que tratou', 
                'Página', 
                'Resultados'            
            );
            $formato = array(  
                'A' => 'dd/mm/yyyy',
                'B' => "hh:mm:ss",
                'I' => '0', 
                'J' => 'dd/mm/yyyy',
                'K' => "hh:mm:ss"
            ); 
            
            $dados = $this->getHistoricoPesquisaPublicacaoView( $request->selected_date_de, $request->selected_date_ate );


        }else if( $request->titulo_excel == 'visualicoes' ){
            $this->autorizacao( 'BASE_EXPORTACOES_VISUALIZACOES' );
            $arquivo = 'Historico_Visualizacoes_Publicacao_';
            $titulo_excel = array(            
                'Data', 
                'Hora', 
                'Usuário', 
                'Departamento',
                'Área',
                'Publicação', 
                'Categoria', 
                'Sub categoria',
                'IP', 
                'Página anterior', 
                'Browser', 
                'Sistema operacional'
            );
            $formato = array( 
                'A' => 'dd/mm/yyyy',
                'B' => "hh:mm:ss"
            );
            $dados = $this->getPesquisaVisualizacao( $request->selected_date_de, $request->selected_date_ate );
        
        }else if( $request->titulo_excel == 'recomendacoes' ){
            $this->autorizacao( 'BASE_EXPORTACOES_RECOMENDACOES' );
            $arquivo = 'Historico_Recomendacoes_da_Publicacao_';
            $titulo_excel = array(            
                'Data', 
                'Horário', 
                'Usuário que recomendou ', 
                'Usuário que recebeu ', 
                'Publicação', 
                'Categoria', 
                'Sub categoria', 
                'Mensagem', 
                'Visualizada'
            );
            $dados = $this->getRecomendacoesPublicacao( $request->selected_date_de, $request->selected_date_ate );
            $formato = array(  
                'A' => 'dd/mm/yyyy',
                'B' => "hh:mm:ss",
                'I' => '0' 
            ); 

        }else if($request->titulo_excel = 'visualizacaoPublicacao'){
            $this->autorizacao( 'BASE_EXPORTACOES_VISUALIZACOES' );
            $arquivo = 'Historico_Visualizacao_Publicacao_';
            $titulo_excel = array(            
                'Data', 
                'Usuário', 
                'Departamento',
                'Área'
            );
            $formato = array( 
                'A' => 'dd/mm/yyyy'
            );
            $dados = $this->getPesquisaVisualizacaoPublicacao( $request->publicacao_id );

        }

        return $excel->downloadExcel($type, $dados->toArray(), $arquivo, $titulo_excel, $formato);   
    }

    private function getDadosPublicacaoView(){
        return $this->dadosPublicacaoView->getDadosPublicacaoView();        
    }

    private function getRecomendacoesPublicacao($de, $ate){
        
        $dados = $this->publicacoesRecomendacoes->getRecomendacoesPublicacao($de, $ate);    

        $dados->map(function ($value, $key) {            
            $value->__set( 'hora', $this->date()->formataHoraExcel($value->hora) );
            $value->__set( 'data', $this->date()->formataDataExcel($value->data) );
        });
        return $dados;          
    }

    private function getHistoricoPesquisaPublicacaoView( $de, $ate ){

        $dados = $this->historicoPesquisaPublicacaoView->getDadosHistoricoPesquisaPublicacaoView( $de, $ate );

        $dados->map(function ($value, $key) {            
            $value->resultados = $this->montaResultados( $value->resultados );
            $value->__unset('id');
            $value->__unset('created_at');

        });

        return $dados;        
    }


    private function getPesquisaVisualizacao( $de, $ate )
    {
        $dados = $this->publicacaoVisualizacao->getVisualizacoesPublicacaoExportacao($de, $ate);

        $dados->map(function ($value, $key) {            
            $value->__set( 'hora', $this->date()->formataHoraExcel($value->hora) );
            $value->__set( 'data', $this->date()->formataDataExcel($value->data) );
        });
        return $dados;        

    }

    private function getPesquisaVisualizacaoPublicacao( $publicacao_id )
    {
        $dados = $this->publicacaoVisualizacao->getPesquisaVisualizacaoPublicacao($publicacao_id);
        $dados->map(function ($value, $key) {            
            $value->__set( 'hora', $this->date()->formataHoraExcel($value->hora) );
          
        });
        return $dados; 
    }

    private function montaResultados( $listas ){
        
        $frase ='';
        foreach ( json_decode( $listas ) as $lista) {

            $titulo = Publicacao::find( $lista->id )->titulo;
            $nota = $lista->nota;

            $montaNota = '';
            if( !is_null($nota) && $nota != '' )
                $montaNota = ' ('. number_format($nota, 2, ",", ".") .'); ';
            else
                $montaNota = '; ';

            $frase .= $titulo.$montaNota;
        }

        return trim($frase, ', ');

    }

}
