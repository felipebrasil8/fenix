<?php 

namespace App\Repositories\Configuracao\Sistema;

use App\Models\Configuracao\Sistema\ParametroGrupo;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Sistema\ParametroGrupoRepositoryInterface;


/**
 * ParametroRepositoryEloquent será responsavel por conversar com model de Parametro
 */
class ParametroGrupoRepositoryEloquent extends BaseRepository implements ParametroGrupoRepositoryInterface
{

	public function model()
	{

		return ParametroGrupo::class;

	}

}