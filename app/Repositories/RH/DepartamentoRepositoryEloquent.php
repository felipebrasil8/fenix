<?php 

namespace App\Repositories\RH;

use App\Models\RH\Departamento;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\RH\DepartamentoRepositoryInterface;


/**
 * AreasRepositoryEloquent será responsavel por conversar com model de Area
 */

class DepartamentoRepositoryEloquent extends BaseRepository implements DepartamentoRepositoryInterface

{

	public function model()
	{
		return Departamento::class;

	}

}