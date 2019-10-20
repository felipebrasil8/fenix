<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Models\BaseDeConhecimento\PublicacoesBusca;
use App\Models\BaseDeConhecimento\PublicacaoTag;
use App\Models\BaseDeConhecimento\PublicacoesHistoricoBusca;
use App\Models\BaseDeConhecimento\PublicacaoColaborador;
use App\Models\BaseDeConhecimento\PublicacaoCategoria;
use App\Models\BaseDeConhecimento\Publicacao;
use App\Http\Controllers\BaseDeConhecimento\PublicacaoController;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;
use App\Models\Core\Icone;

class PublicacoesBuscaController extends PublicacaoController
{

    private $publicacaoBusca;
    private $PublicacaoTag;
    private $publicacoesHistoricoBusca;
    private $publicacaoColaborador;
    private $publicacaoCategoria;

    private $errors;
    private $success;
    private $entidade = 'Publicação Busca';   

    public function __construct(Errors $errors,
                                    Success $success,
                                        PublicacoesBusca $publicacaoBusca, 
                                            PublicacoesHistoricoBusca $publicacoesHistoricoBusca,
                                                Publicacao $publicacao,
                                                    PublicacaoTag $publicacaoTag,
                                                        PublicacaoColaborador $publicacaoColaborador,
                                                            PublicacaoCategoria $publicacaoCategoria
                                      )
    {
        $this->publicacaoBusca = $publicacaoBusca;
        $this->publicacoesHistoricoBusca = $publicacoesHistoricoBusca;
        $this->publicacaoTag =  $publicacaoTag;
        $this->publicacoes =  $publicacao;
        $this->publicacaoColaborador = $publicacaoColaborador;
        $this->errors = $errors;
        $this->success = $success;
        $this->publicacaoCategoria = $publicacaoCategoria;
    }


    /**
     * [busca Retorna view para BUSCA]
     * @param  [type] $busca [Conteudo buscado]
     */
    public function busca( $busca )
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );
        
        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();

        return view('base-de-conhecimento.busca', [
            'busca' => $busca,
            'migalhas' => $this->migalhaDePao('false'),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'can_categoria' => json_encode($this->publicacoes->getPermissaoCategoria()),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'icones' => $icones
        ]);
    }


    /**
     * [tag Retorna view para COLABORADOR]
     * @param  [type] $busca [Colaborador buscada]
     */
    public function colaborador( $busca )
    {   
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();
        
        $colaborador = '@'.$busca;

        return view('base-de-conhecimento.busca', [
            'busca' => $colaborador,
            'migalhas' => $this->migalhaDePao('false'),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'can_categoria' => json_encode($this->publicacoes->getPermissaoCategoria()),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'icones' => $icones
        ]);
    }

    /**
     * [tag Retorna view para TAG]
     * @param  [type] $busca [Tag buscada]
     */
    public function tag( $busca )
    {

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $icones = Icone::select('id', 'icone', 'nome', 'unicode')->orderBy('nome')->get();
        
        $tag = '#'.$busca;

        return view('base-de-conhecimento.busca', [
            'busca' => $tag,
            'migalhas' => $this->migalhaDePao('false'),
            'can' => $this->getPermissaoHabilitarEdicao(),
            'can_categoria' => json_encode($this->publicacoes->getPermissaoCategoria()),
            'categorias' => $this->publicacaoCategoria->categoriaAtiva()->flatten(),
            'icones' => $icones
        ]);
    }

    /**
     * [getBuscaPublicacoes Responsavel por retornar as publicações da pesquisa. Verifica se é uma busca de tag ou busca comum. ]
     * @param  Request $request [Pesquisa do usuario - com # é tag, sem é busca comum)]
     * @return [type]           [description]
     */
    public function getBuscaPublicacoes( Request $request){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );
        
        $tag = strripos($request->busca, '#');
        $colaborador = strripos($request->busca, '@');
        $busca = strtoupper(str_replace('@' , '',(str_replace('#' , '', $request->busca))));
        
        if ( $tag === 0 )
        {
            $publicacoes = $this->publicacoes->getPublicacaoTags( $busca );
        }
        else if ( $colaborador === 0 )
        {
            $publicacoes = $this->publicacoes->getPublicacaoColaborador( $busca );
        }
        else
        {
            $publicacoes = $this->publicacoes->getPublicacoesBuscaPaginate( $busca );
        } 

        if ( !$this->publicacoes->getModoEdicaoAtivos() )
        {
            $this->publicacoesHistoricoBusca->setHistoricoBusca( $this->formatString()->strToUpperCustom($request->busca),  $publicacoes );
        }

        return [
            'publicacoes' => $publicacoes
        ];

    }   

    public function setBuscaTratada(Request $request){
        $this->autorizacao( 'BASE_DASHBOARD_TRATAR_PESQUISAS' );
        $this->publicacoesHistoricoBusca::where( 'busca', '=', $request->busca )
            ->where( 'usuario_id', '=', $request->id )
            ->update( 
            [ 
                'tratada' => true,
                'usuario_alteracao_id' => \Auth::user()->id
            ] 
        );
         return [
            'mensagem' => "Busca tratada com sucesso!"
       ];   
       

    }

}
