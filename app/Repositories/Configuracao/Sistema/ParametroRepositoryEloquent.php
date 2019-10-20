<?php 

namespace App\Repositories\Configuracao\Sistema;

use App\Models\Configuracao\Sistema\Parametro;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Sistema\ParametroRepositoryInterface;


/**
 * ParametroRepositoryEloquent será responsavel por conversar com model de Parametro
 */
class ParametroRepositoryEloquent extends BaseRepository implements ParametroRepositoryInterface
{

	public function model()
	{

		return Parametro::class;

	}

}