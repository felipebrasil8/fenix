<?php 

namespace App\Repositories\Core;

use App\Models\Core\Notificacao;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Core\NotificacaoRepositoryInterface;

class NotificacaoRepositoryEloquent extends BaseRepository implements NotificacaoRepositoryInterface
{

	public function model()
	{

		return Notificacao::class;

	}

}