<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoginUsuario extends Authenticatable
{
	use Notifiable;
    
    protected $table = 'login_usuario';

    protected $fillable = array(
		'nome',
		'usuario_id',
		'password',
		'usuario'
	);
	protected $hidden = ['password', 'identificador', 'perfil_id',];

	/**
   * Overrides the method to ignore the remember token.
   */
	public function setAttribute($key, $value)
	{
	    $isRememberTokenAttribute = $key == $this->getRememberTokenName();
	    if (!$isRememberTokenAttribute)
	    {
	      parent::setAttribute($key, $value);
	    }
	}

}
