<?php 

namespace App\Repositories\Configuracao;

use App\Models\Configuracao\Perfil;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\PerfilRepositoryInterface;


/**
 * PerfilRepositoryEloquent será responsavel por conversar com model de Perfil
 */
class PerfilRepositoryEloquent extends BaseRepository implements PerfilRepositoryInterface
{

	public function model()
	{

		return Perfil::class;

	}

}