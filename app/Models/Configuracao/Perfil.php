<?php

namespace App\Models\Configuracao;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Usuario;
use App\Models\Configuracao\Acesso;
use Carbon\Carbon;

class Perfil extends Model
{
    protected $table = 'perfis';
    protected $fillable = array(
    	'usuario_inclusao_id',
        'usuario_alteracao_id',
        'nome',
		'ativo',
        'todos_dias',
        'horario_inicial',
        'horario_final',
        'configuracao_de_rede'
	);

    public function getCreatedAtAttribute( $value ) {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y - H:i:s');        
        }
        return $value;
    }
    
    public function getUpdatedAtAttribute( $value ) {
        if( !is_null($value)){            
            return Carbon::parse($value)->format('d/m/Y - H:i:s');        
        }
        return $value;
    }

	public function usuarios()
    {        
        return $this->hasMany(Usuario::class);
    }

    public function acessos()
    {
        return $this->belongsToMany(Acesso::class, 'acesso_perfil')->select('acessos.id', 'acessos.nome');
    }
    
    public function getPerfil($id){
        return Perfil::select('perfis.id', 'perfis.nome', 'perfis.created_at', 'perfis.updated_at', 'altecao.nome AS usuario_alteracao', 'inclusao.nome AS usuario_inclusao', 'perfis.configuracao_de_rede', 'perfis.horario_final', 'perfis.horario_inicial', 'perfis.todos_dias')
            ->leftJoin('usuarios AS altecao', 'altecao.id', '=', 'perfis.usuario_alteracao_id')
            ->leftJoin('usuarios AS inclusao', 'inclusao.id', '=', 'perfis.usuario_inclusao_id')
            ->where('perfis.id','=', $id)
            ->first();
        

    }

    public function getPerfis($filtro)
    {
        return Perfil::select('perfis.id','perfis.ativo','perfis.nome')
            ->selectRaw('(SELECT COUNT(*) FROM usuarios WHERE usuarios.perfil_id = perfis.id ) AS usuarios')
            ->selectRaw('(SELECT COUNT(*) FROM acesso_perfil ap WHERE ap.perfil_id = perfis.id ) AS acessos')
            ->queryWhereNome($filtro->nome)
            ->queryWhereAcesso($filtro->acesso)
            ->queryWhereStatus($filtro->status)
            ->queryWhereDiasLogin($filtro->diasLogin)
            ->queryOrderBy($filtro->sort)
            ->paginate($filtro->por_pagina);
    }

    public function scopeQueryOrderBy( $query, $sort )
    {
        if ($sort != ''){
            return $query->orderByRaw($sort);
        }
    }

    public function scopeQueryWhereAcesso( $query, $acesso )
    {
        if ($acesso != ''){
             // return $query->join('acesso_perfil', 'acesso_perfil.perfil_id', '=', 'acesso_perfil.id');
            return $query->join('acesso_perfil', function ($join) use ($acesso) {                            
                $join->on('acesso_perfil.perfil_id', '=', 'perfis.id')
                ->where('acesso_perfil.acesso_id', '=', $acesso);
            });
        }
    }

    public function scopeQueryWhereNome( $query, $nome )
    {
        if ($nome != ''){
            return $query->whereRaw('sem_acento(nome) LIKE UPPER(sem_acento(\'%'.$nome.'%\'))');
        }
    }

    public function scopeQueryWhereStatus( $query, $status )
    {
        if ($status != 'todos'){
          $query->where('ativo', '=', $status);
        }
    }

    public function scopeQueryWhereDiasLogin( $query, $diasLogin )
    {
        if ($diasLogin != ''){
          $query->where('todos_dias', '=', ($diasLogin == 'true')?true:false );
        }
    }


}
