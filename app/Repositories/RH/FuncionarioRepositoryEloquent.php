<?php 

namespace App\Repositories\RH;

use App\Models\RH\Funcionario;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\RH\FuncionarioRepositoryInterface;


/**
 * FuncionarioRepositoryEloquent será responsavel por conversar com model de Funcionario
 */
class FuncionarioRepositoryEloquent extends BaseRepository implements FuncionarioRepositoryInterface
{

	public function model()
	{

		return Funcionario::class;

	}

}