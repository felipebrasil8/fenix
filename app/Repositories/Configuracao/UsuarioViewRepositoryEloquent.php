<?php 

namespace App\Repositories\Configuracao;

use App\Models\Configuracao\UsuarioView;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\UsuarioViewRepositoryInterface;


/**
 * UsuarioRepositoryEloquent será responsavel por conversar com model de Usuario
 */
class UsuarioViewRepositoryEloquent extends BaseRepository implements UsuarioViewRepositoryInterface
{

	public function model()
	{

		return UsuarioView::class;

	}

}