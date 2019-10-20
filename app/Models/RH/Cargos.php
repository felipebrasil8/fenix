<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;
use App\Models\RH\Funcionario;
use App\Models\RH\Departamentos;

class Cargos extends Model
{

    protected $fillable = array(
		'funcionario_id',
		'departamento_id',
		'nome',
		'descricao'
	);

	public function funcionario()
	{			   
		return $this->belongsTo(Funcionario::class, 'id');
	}

    public function departamentos()
    {
        return $this->hasMany(Departamentos::class);
    }

    public function getCargos()
    {
        return Cargos::select('id', 'nome', 'descricao')
                        ->where('ativo', '=', true)
                        ->orderBy('nome')
                        ->get();
    }
}
