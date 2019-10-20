<?php 

namespace App\Repositories\RH;

use App\Models\RH\Area;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\RH\AreaRepositoryInterface;


/**
 * AreasRepositoryEloquent será responsavel por conversar com model de Area
 */
class AreaRepositoryEloquent extends BaseRepository implements AreaRepositoryInterface
{

	public function model()
	{

		return Area::class;

	}

}