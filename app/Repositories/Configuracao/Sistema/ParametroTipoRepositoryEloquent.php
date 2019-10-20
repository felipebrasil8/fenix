<?php 

namespace App\Repositories\Configuracao\Sistema;

use App\Models\Configuracao\Sistema\ParametroTipo;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Sistema\ParametroTipoRepositoryInterface;


/**
 * ParametroRepositoryEloquent será responsavel por conversar com model de Parametro
 */
class ParametroTipoRepositoryEloquent extends BaseRepository implements ParametroTipoRepositoryInterface
{

	public function model()
	{

		return ParametroTipo::class;

	}

}