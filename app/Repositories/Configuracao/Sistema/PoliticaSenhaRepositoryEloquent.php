<?php 

namespace App\Repositories\Configuracao\Sistema;

use App\Models\Configuracao\Sistema\PoliticaSenha;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Configuracao\Sistema\PoliticaSenhaRepositoryInterface;


/**
 * ComplexidadeSenhaRepositoryEloquent será responsavel por conversar com model de ComplexidadeSenha
 */
class PoliticaSenhaRepositoryEloquent extends BaseRepository implements PoliticaSenhaRepositoryInterface
{

	public function model()
	{

		return PoliticaSenha::class;

	}

}