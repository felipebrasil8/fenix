<?php 

namespace App\Repositories\Configuracao\Ticket;

use App\Models\Configuracao\Ticket\Prioridade;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Ticket\PrioridadeRepositoryInterface;

/**
 * PrioridadeRepositoryEloquent será responsavel por conversar com model de Prioridade
 */
class PrioridadeRepositoryEloquent extends BaseRepository implements PrioridadeRepositoryInterface
{

	public function model()
	{

		return Prioridade::class;

	}

}