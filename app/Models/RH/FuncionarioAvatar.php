<?php

namespace App\Models\RH;

use Illuminate\Database\Eloquent\Model;

class FuncionarioAvatar extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'funcionarios_avatars';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
		'funcionario_id',
        'usuario_inclusao_id',
		'avatar_grande',
		'avatar_pequeno',
    );
    
    public function funcionario()
    {
        return $this->hasOne(Funcionario::class);
    }

    /**
     * Método que retorna os histórico de avatares e seus dados. 
     *
     * @param [int] $id
     * @return Collection 
     */
    public function getHistoricoAvatares( $id ){
        
        return FuncionarioAvatar::select('funcionarios_avatars.id', 'funcionarios_avatars.created_at', 'usuarios.nome', 'funcionarios_avatars.usuario_inclusao_id')
        ->join('usuarios', 'usuarios.id', '=', 'funcionarios_avatars.usuario_inclusao_id')
        ->where('funcionarios_avatars.funcionario_id', '=', $id)->orderBy('funcionarios_avatars.created_at', 'desc')->get();    
    }

    public function getAvatarGrande( $id )
    {        
        return FuncionarioAvatar::where('id','=', $id)
            ->select('avatar_grande')
            ->first()
            ->avatar_grande;
    }    

    public function getAvatarPequeno( $id )
    {
        return FuncionarioAvatar::where('id','=', $id)
            ->select('avatar_pequeno')
            ->first()
            ->avatar_pequeno;
    }
}
