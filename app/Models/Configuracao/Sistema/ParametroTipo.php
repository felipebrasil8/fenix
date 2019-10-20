<?php

namespace App\Models\Configuracao\Sistema;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Sistema\Parametro;
use App\Models\Configuracao\Sistema\ParametroGrupo;
use App\Models\Configuracao\Sistema\ParametroTipo;

class ParametroTipo extends Model
{
    protected $table = 'parametros_tipo';
    protected $fillable = array(
		'nome',
		'ativo'
	);

	public function parametros()
    {
        return $this->hasMany(Parametro::class, 'parametro_tipo_id');
    }
}
