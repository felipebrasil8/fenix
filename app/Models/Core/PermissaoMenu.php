<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class PermissaoMenu extends Model
{
	protected $table = 'permissao_menu';
    protected $fillable = array(
		'id',
		'nome',
		'descricao',
		'icone',
		'url',
		'nivel',
		'ordem',
		'menu_pai',
		'usuario_id'
	);
}
