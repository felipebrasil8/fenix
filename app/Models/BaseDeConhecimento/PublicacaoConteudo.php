<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseDeConhecimento\PublicacaoTag;
use App\Models\BaseDeConhecimento\PublicacaoColaborador;
use App\Models\BaseDeConhecimento\PublicacaoConteudoTipo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Core\ImagemController;
use Illuminate\Support\Facades\DB;

class PublicacaoConteudo extends Model
{
    use SoftDeletes;

	protected $table = 'publicacoes_conteudos';

    protected $softDelete = true;

	protected $fillable = array(
        'id',
        'usuario_inclusao_id',
        'usuario_alteracao_id',
        'publicacao_conteudo_tipo_id',
        'dados',
        'conteudo',
        'adicional',
        'ordem',
        'rascunho'
    );

 	protected $hidden = array(
        'created_at',        
        'publicacao_id',
        'delete_at',
        'publicacao_conteudo_tipo_id',
    );

    public function getConteudo($id, $rascunho = false)
    {
        $publicacaoTag = new PublicacaoTag();
        $publicaoColaborador = new PublicacaoColaborador(); 
    
    	return [ 
            'tags' => $publicacaoTag->getTagsPublicacao($id, $rascunho),
    		'colaboradores' => $publicaoColaborador->getColaboradorPublicacao($id, $rascunho),
    		'conteudo' => $this->formataConteudo($this->getConteudoPublicacao($id, $rascunho)),
        ];
    }

    public function getConteudoPublicacao($id, $rascunho = false)
    {
    	return PublicacaoConteudo::where('publicacoes_conteudos.publicacao_id','=', $id)
            ->where('rascunho', '=', $rascunho)
            ->select('publicacoes_conteudos.id', 'publicacoes_conteudos_tipos.nome', 'publicacoes_conteudos.dados', 'publicacoes_conteudos.conteudo', 'publicacoes_conteudos.adicional')
            ->leftJoin('publicacoes_conteudos_tipos', 'publicacoes_conteudos.publicacao_conteudo_tipo_id', '=', 'publicacoes_conteudos_tipos.id')
            ->orderBy('publicacoes_conteudos.ordem')
            ->get();
    }

    public function getOrdemConteudoPublicacao($id)
    {
        return PublicacaoConteudo::where('publicacao_id','=', $id)
            ->select('publicacoes_conteudos.ordem')
            ->max('ordem');
    }

    public function getTipoConteudo($id)
    {
        return PublicacaoConteudo::where('publicacoes_conteudos.id','=', $id)
            ->select('publicacoes_conteudos_tipos.nome', 'publicacoes_conteudos.publicacao_id')
            ->join('publicacoes_conteudos_tipos', 'publicacoes_conteudos.publicacao_conteudo_tipo_id', '=', 'publicacoes_conteudos_tipos.id')
            ->first();
    }

    public function formataConteudo($conteudo)
    {
        $conteudo->map(function ($item, $key) {
            // Para conteúdo do tipo TEXTO
            if ($item->nome == 'TEXTO')
            {
                // Substitui o caractere TAB para visualização no HTML
                $item->conteudo = str_replace(chr(9),'&emsp;', $item->conteudo);
                $item->conteudo = str_replace(chr(32).chr(32),'&nbsp;&nbsp;', $item->conteudo);
            }
            else if ($item->nome == 'IMAGEM')
            {
                // Retirar base64 do retorno para reduzir tamanho da requisição
                if ( $item->dados != '')
                {
                    $imgGrande = json_decode($item->dados)->original;
                    $item->width = ImagemController::getWidth($imgGrande);
                    $item->height = ImagemController::getHeight($imgGrande);
                    $item->dados = true;
                }
                else
                {
                    $item->dados = false;
                }
            }
            else if ($item->nome == 'IMAGEM COM LINK')
            {
                // Retirar base64 do retorno para reduzir tamanho da requisição
                if ( $item->dados != '')
                {
                    $item->dados = true;
                }
                else
                {
                    $item->dados = false;
                }
            }
        });
        return $conteudo;
    }

    public function getImagemOriginal( $id ){

        $imagem = $this->getImagem($id);
        
        if ( !empty($imagem) )
            return json_decode( $imagem )->original;
        
        return null;
        
    }

    public function getImagemMiniatura( $id ){

        $imagem = $this->getImagem($id);

        if ( !empty($imagem) )
            return json_decode( $imagem )->miniatura;
        
        return null;
    }

    private function getImagem( $id ){
        return PublicacaoConteudo::where('publicacoes_conteudos.id','=', $id)
            ->select('dados')
            ->first()
            ->dados;
    }

    public function getConteudoPublicacaoRascunho($id, $rascunho, $usuario_inclusao)
    {
        return PublicacaoConteudo::where('publicacao_id','=', $id)
            ->where('rascunho', '=', $rascunho)
            ->selectRaw('publicacao_id, TRUE AS rascunho, '.$usuario_inclusao.' AS usuario_inclusao_id, publicacao_conteudo_tipo_id, dados, conteudo, adicional, ordem');
    }

    public function insertRascunho($query)
    {
        $insertPublicacoes = 'INSERT INTO publicacoes_conteudos (publicacao_id, rascunho, usuario_inclusao_id, publicacao_conteudo_tipo_id, dados, conteudo, adicional, ordem) '.$query->toSql();
        \DB::insert($insertPublicacoes, $query->getBindings());
    }
}   