<?php

namespace App\Http\Controllers\BaseDeConhecimento;

use Illuminate\Http\Request;
use App\Http\Requests\BaseDeConhecimento\TagRequest;

use App\Http\Controllers\Controller;
use App\Models\BaseDeConhecimento\PublicacaoTag;

use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;


class TagController extends Controller
{

    private $publicacaoTag;
    private $errors;
    private $success;
    private $entidade = 'Tag';
   
    public function __construct(Errors $errors,
                                    Success $success,
                                        PublicacaoTag $publicacaoTag )
    {
    	$this->publicacaoTag = $publicacaoTag;
        $this->errors = $errors;
        $this->success = $success;
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );
        
        $this->destroy($request->publicacao_id, $request->rascunho);

        $dados = array();
       
        if($request->tag != ""){
            
            $tags = explode(",", $request->tag);
            $tags = array_map('trim', $tags);
            $tags = array_unique($tags);  

            foreach ($tags as $value) {
                
                if(strlen($value) < 50){
                    if(strlen($value) > 0) {
                        array_push($dados, 
                        [
                            'usuario_inclusao_id' => \Auth::user()->id,
                            'tag' => trim($value),
                            'publicacao_id' => $request->publicacao_id,
                            'rascunho' => $request->rascunho
                        ]);
                    }
                }else{
                    return \Response::json( ['errors' => [['Cada TAG deve conter apenas 50 caracteres']]] ,500);
                }

            }

            $this->publicacaoTag->insert($dados);
            return [
                'mensagem' => $this->success->msgStore( $this->entidade ), 
                'id' =>$request->publicacao_id
            ];
        }   
            return [
                'mensagem' => $this->success->msgStore( $this->entidade ),
                'id' =>$request->publicacao_id
            ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        return $this->getTags($id, false);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $rascunho)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );
        
        $this->publicacaoTag->where('publicacao_id', '=', $id)->where('rascunho', '=', $rascunho)->delete();
    }

    private function getTags( $id, $rascunho )
    {
        return [
            'tags' => $this->publicacaoTag->getTagsPublicacao($id, $rascunho)->implode('tag', ', ')
        ];
    }

    public function getRascunho($id)
    {
        $this->autorizacao( 'BASE_PUBLICACOES_EDITAR' );

        return $this->getTags($id, true);
    }
}
