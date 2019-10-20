<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Usuario;

class PermissaoUsuario extends Model
{
	protected $table = 'permissao_usuario';
    protected $fillable = array(
		'usuario_id',
		'perfil_id',
		'perfil_nome',
		'permissao_id',
		'permissao_descricao',
		'permissao_identificador',
		'permissao',
		'menu_nome'
	);

    //lista as permissões ligadas ao usuario logado, usando a tabela permissao_usuario
    public function listaPermissoes()
    {	
    	return PermissaoUsuario::where('usuario_id', '=', auth()->id())->get();
	}

	public function permissaoIdentificador( $usuario, $identificador )
	{
		if( !isset($usuario) || $identificador == '' )
		{
			return false;
		}
		else
		{
			$permissaoUsuario = PermissaoUsuario::where('usuario_id', '=', $usuario->id)->where('permissao_identificador', '=', $identificador)->get();

			if( !isset($permissaoUsuario) )
			{
				return false;
			}
			else
			{
				// foreach para acessar objeto
				foreach ($permissaoUsuario as $permissao)
				{
					return $permissao->permissao;
				}
			}
		}
	}
}
