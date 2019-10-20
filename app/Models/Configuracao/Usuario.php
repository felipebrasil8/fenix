<?php

namespace App\Models\Configuracao;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Perfil;

class Usuario extends Model
{
    protected $fillable = array(
    	'usuario_inclusao_id',
		'usuario_alteracao_id',
		'funcionario_id',
		'perfil_id',
		'nome',
		'password',
		'usuario',
		'ativo',
		'senha_alterada',
		'visualizado_senha_alterada',
		'solicitar_senha_icone',
		'solicitar_senha_texto'
	);

	protected $hidden = ['password'];

	public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

	public function notificacao()
    {
        return $this->belongsTo(Notificacao::class);
    }

    public function historicos()
    {
        return $this->hasMany('App\Models\Ticket\TicketHistorico');
	}

	public function funcionarios(){
        return $this->belongsTo('App\Models\RH\Funcionario', 'funcionario_id');        
    }


    /**
     * @param int $user_id
     * @return usuario[]
    */
    public function getDepartamento( $user_id )
    {
        return Usuario::where( 'usuarios.id', '=', $user_id )
            ->join('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
            ->join('cargos', 'funcionarios.cargo_id', '=', 'cargos.id')
            ->select('cargos.departamento_id')
            ->first();
    }

    /**
     * @param int $user_id
     * @return usuario[]
    */
    public function getCargo( $user_id )
    {
        return Usuario::where( 'usuarios.id', '=', $user_id )
            ->join('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
            ->join('cargos', 'funcionarios.cargo_id', '=', 'cargos.id')
            ->select('cargos.id')
            ->first();
    }
    
    /**
     * @param string $usuario
     * @return usuario[]
    */
    public function usuarioAtivo( $usuario )
    {
        return Usuario::where('usuarios.usuario', '=', $usuario)
            ->select( 'ativo' )
            ->get();
    }
    public function GetUsuarioPerfil( $usuario )
    {
        return Usuario::where('usuarios.usuario', '=', $usuario)
            ->select( 'usuarios.nome', 'perfis.nome as perfil' )
            ->leftjoin('perfis', 'perfis.id', '=', 'usuarios.perfil_id')
            ->first();
    }


    public function getUsuariosDepartamentos( $ativo = true )
    {
        return Usuario::where( 'usuarios.ativo', '=', $ativo )
                    ->join('funcionarios', 'usuarios.funcionario_id', '=', 'funcionarios.id')
                    ->join('cargos', 'funcionarios.cargo_id', '=', 'cargos.id')
                    ->join('departamentos', 'cargos.departamento_id', '=', 'departamentos.id')
                    ->select('usuarios.id AS usuarios_id', 'usuarios.nome AS usuarios_nome', 
                                'funcionarios.id AS funcionarios_id', 'funcionarios.nome AS funcionarios_nome', 
                                'cargos.id AS cargos_id', 'cargos.nome AS cargos_nome',
                                'departamentos.id AS departamentos_id', 'departamentos.nome AS departamentos_nome')
                    ->get();
    }

}