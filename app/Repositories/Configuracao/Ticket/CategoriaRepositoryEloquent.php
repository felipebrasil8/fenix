<?php 

namespace App\Repositories\Configuracao\Ticket;


use App\Models\Configuracao\Ticket\Categoria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Ticket\CategoriaRepositoryInterface;

/**
 * StatusRepositoryEloquent será responsavel por conversar com model de Status
 */
class CategoriaRepositoryEloquent extends BaseRepository implements CategoriaRepositoryInterface
{

	public function model()
	{

		return Categoria::class;

	}

}