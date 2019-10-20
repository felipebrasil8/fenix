<?php

namespace App\Models\Configuracao;

use Illuminate\Database\Eloquent\Model;

class AcessoPerfil extends Model
{
    protected $table = 'acesso_perfil';
    protected $fillable = array(
    	'usuario_inclusao_id',
		'acesso_id',
		'perfil_id'
	);
}
