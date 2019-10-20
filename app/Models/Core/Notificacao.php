<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    protected $table = 'notificacoes';

    /**
     * Variável para querys não adicionarem column created_at e updated_at
     */
    public $timestamps = false;

    protected $fillable = array(
		'created_at', 
		'usuario_inclusao_id', 
		'usuario_id', 
		'titulo', 
		'mensagem', 
		'modulo', 
		'url', 
		'icone', 
		'cor', 
		'visualizada', 
		'notificada', 
		'dt_visualizada', 
		'dt_notificada'
	);
	
	public function usuarios()
    {        
        return $this->hasMany(Usuario::class);
    }
}
