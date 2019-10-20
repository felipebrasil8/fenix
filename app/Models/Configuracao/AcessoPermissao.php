<?php

namespace App\Models\Configuracao;

use Illuminate\Database\Eloquent\Model;

class AcessoPermissao extends Model
{
    protected $table = 'acesso_permissao';
    protected $fillable = array(
    	'usuario_inclusao_id',
		'acesso_id',
		'permissao_id'
	);
}
