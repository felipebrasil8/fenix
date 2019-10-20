<?php 

namespace App\Repositories\Configuracao;

use App\Models\Configuracao\Usuario;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\UsuarioRepositoryInterface;


/**
 * UsuarioRepositoryEloquent será responsavel por conversar com model de Usuario
 */
class UsuarioRepositoryEloquent extends BaseRepository implements UsuarioRepositoryInterface
{

	public function model()
	{

		return Usuario::class;

	}

}