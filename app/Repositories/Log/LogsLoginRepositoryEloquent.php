<?php 

namespace App\Repositories\Log;

use App\Models\Core\Login;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Log\LogsLoginRepositoryInterface;


/**
 * LogRepositoryEloquent será responsavel por conversar com model de Login
 */
class LogsLoginRepositoryEloquent extends BaseRepository implements LogsLoginRepositoryInterface
{

	public function model()
	{

		return Login::class;

	}

}