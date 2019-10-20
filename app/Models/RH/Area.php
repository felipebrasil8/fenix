<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use App\Models\RH\Funcionario;
use App\Models\Configuracao\Usuario;
use App\Models\RH\Departamento;



class Area extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
		'nome',
		'descricao',
		'ativo',
		'funcionario_id'
	);
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $hidden = array(
		'funcionario',
		'funcionario_id',
		'created_at',
		'updated_at',
		'usuario_alteracao_id',
		'usuario_inclusao_id',
	);
    
    /**
     * Método que valida se o usuário esta ativo
    * @return void 
    */
	public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function usuarioInclusao()
    {
        return $this->belongsTo(Usuario::class, 'usuario_inclusao_id');
    }

    public function usuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'usuario_alteracao_id');
    }

	public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
    
}
