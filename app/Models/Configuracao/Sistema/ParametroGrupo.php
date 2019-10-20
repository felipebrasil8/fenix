<?php

namespace App\Models\Configuracao\Sistema;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Sistema\Parametro;
use App\Models\Configuracao\Sistema\ParametroGrupo;
use App\Models\Configuracao\Sistema\ParametroTipo;

class ParametroGrupo extends Model
{
	protected $table = 'parametros_grupo';
    protected $fillable = array(
		'nome',
		'ativo'
	);

	public function parametros()
    {
        return $this->hasMany(Parametro::class, 'parametro_grupo_id');
    }
}
