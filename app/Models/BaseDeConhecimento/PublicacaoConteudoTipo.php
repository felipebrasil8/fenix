<?php

namespace App\Models\BaseDeConhecimento;

use Illuminate\Database\Eloquent\Model;

class PublicacaoConteudoTipo extends Model
{
    protected $table = 'publicacoes_conteudos_tipos';

	protected $fillable = array(
        'id',
        'nome'
    );

 	protected $hidden = array(
        'created_at',        
        'updated_at',
        'usuario_inclusao_id'
    );

 	public function getNomeAttribute($value)
    {
        return ucfirst(strtolower($value));
    }

    public function getConteudoTipo()
    {
    	return PublicacaoConteudoTipo::select('id', 'nome')
    		->orderBy('nome')
            ->get();
    }
}
