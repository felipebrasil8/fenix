<?php 

namespace App\Repositories\Configuracao;

use App\Models\Configuracao\Acesso;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\AcessoRepositoryInterface;


/**
 * AcessoRepositoryEloquent será responsavel por conversar com model de Acesso
 */
class AcessoRepositoryEloquent extends BaseRepository implements AcessoRepositoryInterface
{

	public function model()
	{

		return Acesso::class;

	}

}