<?php 

namespace App\Repositories\Configuracao;

use App\Models\Configuracao\AcessoPerfil;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\AcessoPerfilRepositoryInterface;


/**
 * AcessoRepositoryEloquent será responsavel por conversar com model de Acesso
 */
class AcessoPerfilRepositoryEloquent extends BaseRepository implements AcessoPerfilRepositoryInterface
{

	public function model()
	{

		return AcessoPerfil::class;

	}

}