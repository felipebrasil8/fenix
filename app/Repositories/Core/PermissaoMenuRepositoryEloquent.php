<?php 

namespace App\Repositories\Core;

use App\Models\Core\PermissaoMenu;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Core\PermissaoMenuRepositoryInterface;


/**
 * UsuarioRepositoryEloquent será responsavel por conversar com model de Usuario
 */
class PermissaoMenuRepositoryEloquent extends BaseRepository implements PermissaoMenuRepositoryInterface
{

	public function model()
	{

		return PermissaoMenu::class;

	}

}