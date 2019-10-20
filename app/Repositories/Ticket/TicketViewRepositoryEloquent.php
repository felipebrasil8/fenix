<?php 

namespace App\Repositories\Ticket;

use App\Models\Ticket\TicketView;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Ticket\TicketRepositoryInterface;


/**
 * AreasRepositoryEloquent será responsavel por conversar com model de Area
 */
class TicketViewRepositoryEloquent extends BaseRepository implements TicketViewRepositoryInterface
{

	public function model()
	{

		return TicketView::class;

	}

}