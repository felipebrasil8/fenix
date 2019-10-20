<?php 

namespace App\Repositories\Configuracao\Ticket;

use App\Models\Configuracao\Ticket\Status;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Ticket\StatusRepositoryInterface;

/**
 * StatusRepositoryEloquent será responsavel por conversar com model de Status
 */
class StatusRepositoryEloquent extends BaseRepository implements StatusRepositoryInterface
{

	public function model()
	{

		return Status::class;

	}

}