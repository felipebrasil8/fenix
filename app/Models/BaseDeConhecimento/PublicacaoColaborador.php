<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BaseDeConhecimento\Publicacao;

class PublicacaoColaborador extends Publicacao
{
   	protected $table = 'publicacoes_colaboradores';

	protected $fillable = array(
      
        'id',
        'tag',
        'usuario_inclusao_id' ,
        'publicacao_id', 
        'funcionario_id',
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

    public function getColaboradorPublicacao($id, $rascunho = false){

    	return PublicacaoColaborador::where('publicacao_id','=', $id)
            ->where('rascunho', '=', $rascunho)
            ->select('publicacoes_colaboradores.funcionario_id', 'publicacoes_colaboradores.rascunho', 'funcionarios.nome')
            ->leftJoin('funcionarios',
            	'publicacoes_colaboradores.funcionario_id', '=', 'funcionarios.id')
            ->orderBy('funcionarios.nome')
            ->get();           
    }

    public function getColaboradorPublicacaoRascunho($id, $rascunho, $usuario_inclusao)
    {
        return PublicacaoColaborador::where('publicacao_id','=', $id)
            ->where('rascunho', '=', $rascunho)
            ->selectRaw('publicacao_id, funcionario_id, TRUE AS rascunho, '.$usuario_inclusao.' AS usuario_inclusao_id');           
    }
 
    public function queryColaborador( $isBusca = false, $filtroCargo = true) 
    {
        return PublicacaoColaborador::
            select( 'id' )
            ->leftJoin('publicacoes', 'publicacoes.id', '=', 'publicacoes_colaboradores.publicacao_id' )
            ->queryWherePublicada();
    
    } 

    public function insertRascunho($query)
    {
        $insertColaboradores = 'INSERT INTO publicacoes_colaboradores (publicacao_id, funcionario_id, rascunho, usuario_inclusao_id) '.$query->toSql();
        \DB::insert($insertColaboradores, $query->getBindings());           
    }
}
