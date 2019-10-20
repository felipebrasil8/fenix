<?php 

namespace App\Repositories\Configuracao\Sistema;

use App\Models\Configuracao\Sistema\ParametroView;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Sistema\ParametroViewRepositoryInterface;


/**
 * ParametroRepositoryEloquent será responsavel por conversar com model de Parametro
 */
class ParametroViewRepositoryEloquent extends BaseRepository implements ParametroViewRepositoryInterface
{

	public function model()
	{

		return ParametroView::class;

	}

}