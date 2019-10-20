<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseDeConhecimento\Publicacao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PublicacaoTag extends Publicacao
{

	protected $table = 'publicacoes_tags';

	protected $fillable = array(
      
        'id',
        'tag',
        'usuario_inclusao_id',
        'rascunho'  
    );

 	protected $hidden = array(
        'created_at',        
        'publicacao_id',
        'delete_at'
    );

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    public function setUpdatedAt($value)
    {
        // Do nothing.
    }

    public function getTagsPublicacao($id, $rascunho = false){

    	return PublicacaoTag::where('publicacao_id','=', $id)
            ->select('id', 'tag')
            ->where('rascunho', '=', $rascunho)
            ->get();
    }

    public function getTagsPublicacaoRascunho($id, $rascunho, $usuario_inclusao)
    {
        return PublicacaoTag::where('publicacao_id','=', $id)
            ->where('rascunho', '=', $rascunho)
            ->selectRaw('publicacao_id, tag, TRUE AS rascunho, '.$usuario_inclusao.' AS usuario_inclusao_id');           
    }

    public function insertRascunho($query)
    {
        $insertTags = 'INSERT INTO publicacoes_tags (publicacao_id, tag, rascunho, usuario_inclusao_id) '.$query->toSql();
        \DB::insert($insertTags, $query->getBindings());     
    }
}
