<?php 

namespace App\Repositories\Core;

use App\Models\Core\Permissao;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Core\PermissaoRepositoryInterface;


/**
 * UsuarioRepositoryEloquent será responsavel por conversar com model de Usuario
 */
class PermissaoRepositoryEloquent extends BaseRepository implements PermissaoRepositoryInterface
{

	public function model()
	{

		return Permissao::class;

	}

}