<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Acesso;

class Permissao extends Model
{
    protected $table = 'permissoes';
    protected $fillable = array(
    	'usuario_inclusao_id',
		'usuario_alteracao_id',
		'menu_id',
		'descricao',
		'identificador'		
	);

	public function acessos()
    {
    	return $this->belongsToMany(Acesso::class, 'acesso_permissao');
    }




}
