<?php 

namespace App\Repositories\Configuracao\Ticket;

use App\Models\Configuracao\Ticket\Campo;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Ticket\CampoRepositoryInterface;

/**
 * CampoRepositoryEloquent será responsavel por conversar com model de Campo
 */
class CampoRepositoryEloquent extends BaseRepository implements CampoRepositoryInterface
{

	public function model()
	{

		return Campo::class;

	}

}