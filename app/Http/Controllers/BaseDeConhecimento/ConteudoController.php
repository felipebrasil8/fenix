<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Models\BaseDeConhecimento\PublicacaoConteudo;
use App\Models\BaseDeConhecimento\PublicacaoConteudoTipo;

use App\Http\Requests\BaseDeConhecimento\ConteudoTipoRequest;
use App\Http\Controllers\Core\ImagemController;

class ConteudoController extends Controller
{

    private $publicacaoConteudo;
    private $publicacaoConteudoTipo;

    private $errors;
    private $success;
    private $imagem;
    private $entidade = 'Conteúdo';

    public function __construct( Errors $errors, 
                                    Success $success, 
                                        ImagemController $imagem,
                                         PublicacaoConteudo $publicacaoConteudo,
                                          PublicacaoConteudoTipo $publicacaoConteudoTipo  )
    {
        $this->publicacaoConteudo = $publicacaoConteudo;
        $this->publicacaoConteudoTipo = $publicacaoConteudoTipo;
        $this->imagem = $imagem;       
        $this->errors = $errors;
        $this->success = $success;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        return $this->getConteudo($id, false);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        $tipo = $this->publicacaoConteudo->getTipoConteudo($id);
       
        switch ($tipo->nome) {
            case 'IMAGEM':
                $this->setImagem($id, $request);
                break;
            case 'IMAGEM COM LINK':
                $this->setImagemLink($id, $request);
                break;
            case 'TEXTO':
                $this->setTexto($id, $request);
                break;
            default:
                break;
        }
         
        return [
            'mensagem' => $this->success->msgStore( $this->entidade ),
            'publicacao_id' => $tipo->publicacao_id
        ];


    }

    public function setOrdem(Request $request){
        
        $ordem = $request->conteudo;
        $cont = 1;
        foreach ($ordem as $item) {
            $this->publicacaoConteudo::whereId($item['id'])
                 ->update(['ordem' => $cont]);
            $cont++;  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        $validator = Validator::make( ['id' => $id], [            
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()){
            return \Response::json(['errors' => [ $validator->errors()->all() ]], 404);
        }

        $publicacaoConteudo = $this->publicacaoConteudo->where('id', '=', $id)->first();
        $publicacaoConteudo->ordem = 0;
        $publicacaoConteudo->save();
        $publicacaoConteudo->delete();
        
        return [
            'mensagem' => $this->success->msgDestroy( $this->entidade ), 
            'id' => $id  
        ];
    }

    public function setConteudoTipo(ConteudoTipoRequest $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_CONTEUDO_EDITAR' );
        $ordem = $this->publicacaoConteudo->getOrdemConteudoPublicacao( $request->publicacao_id );
        $this->publicacaoConteudo->insert(
             [
                'usuario_inclusao_id' => \Auth::user()->id,
                'publicacao_id' => $request->publicacao_id,
                'publicacao_conteudo_tipo_id' => $request->conteudo_tipo_id,
                'ordem' => ($ordem + 1),
                'rascunho' => (isset($request->rascunho) ? $request->rascunho :false)
             ]
        );

        return [
            'mensagem' => $this->success->msgStore( $this->entidade )
        ];
    }

    public function getImagemConteudoMiniatura($id){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $base64 = $this->publicacaoConteudo->getImagemMiniatura($id);

        if ( !empty($base64) )
            return ImagemController::getResponse($base64);

        return null;

    }

    public function getImagemConteudoOriginal($id){

        $this->autorizacao( 'BASE_PUBLICACOES_VISUALIZAR' );

        $base64 = $this->publicacaoConteudo->getImagemOriginal($id);

        if ( !empty($base64) )
            return ImagemController::getResponse($base64);
        return null;

    }


    private function setImagem($id, $request){
       
        $file = $request->image;
        $base64 = $this->imagem->geraImagemBase64( $file, 1200, 'jpeg' );
        $base64_miniatura =  $this->imagem->geraImagemBase64( $file, 800, 'jpeg' );
        $this->publicacaoConteudo::whereId($id)
         ->update( 
            [ 
                'usuario_alteracao_id' => \Auth::user()->id,
                'dados' => json_encode(
                    [   
                        'original' => $base64,
                        'miniatura' => $base64_miniatura
                    ]),
            ] 
        );
          
    }

    private function setImagemLink($id, $request)
    {   
        $dados = [ 
                'usuario_alteracao_id' => \Auth::user()->id,
                'adicional' => $request->adicional,
            ];

        if( $this->validaUpdateImagemLink($id) || isset($request->image) )
        {
            $file = $request->image;
            $base64_miniatura = $this->imagem->geraImagemBase64( $file, 800, 'jpeg' );
            
            $dados = array_merge( $dados, ['dados' => json_encode(
                                                [   
                                                   'original' => $base64_miniatura
                                                ])
                                            ] );  
        }

        $this->publicacaoConteudo::whereId($id)
        ->update( $dados );
      
    }

    private function setTexto($id, $request){
       
        $this->publicacaoConteudo::whereId($id)
         ->update( 
            [ 
                'usuario_alteracao_id' => \Auth::user()->id,
                'conteudo' => $request->conteudo,
            ] 
        );
        return true;

    }

    private function validaUpdateImagemLink($id)
    {
        // Verifica se já tem imagem para não deixar obrigatorio
        $img = $this->publicacaoConteudo->getImagemOriginal($id);

        if( isset($img) )
        {
            return false;
        }

        return true;   
    }

    private function getConteudo( $id, $rascunho )
    {
        return  [
            'conteudo' => $this->publicacaoConteudo->getConteudo($id, $rascunho),
            'conteudo_tipo' => $this->publicacaoConteudoTipo->getConteudoTipo(),
        ];
    }

    public function getRascunho($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_RASCUNHO' );

        return $this->getConteudo($id, true);
    }

}
