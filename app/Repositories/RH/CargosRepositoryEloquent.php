<?php 

namespace App\Repositories\RH;

use App\Models\RH\Cargos;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\RH\CargosRepositoryInterface;


/**
 * CargosRepositoryEloquent será responsavel por conversar com model de Cargos
 */
class CargosRepositoryEloquent extends BaseRepository implements CargosRepositoryInterface
{

	public function model()
	{

		return Cargos::class;

	}

}