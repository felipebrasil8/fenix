<?php 

namespace App\Repositories\Ticket;

use App\Models\Ticket\Ticket;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Ticket\TicketRepositoryInterface;

class TicketRepositoryEloquent extends BaseRepository implements TicketRepositoryInterface
{
	public function model()
	{
		return Ticket::class;
	}
}