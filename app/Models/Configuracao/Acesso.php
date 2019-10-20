<?php

namespace App\Models\Configuracao;

use Illuminate\Database\Eloquent\Model;
use App\Models\Configuracao\Perfil;
use App\Models\Core\Permissao;
use App\Models\Core\PermissaoMenu;
use Carbon\Carbon;

class Acesso extends Model
{


    protected $fillable = array(
		'usuario_inclusao_id',
		'usuario_alteracao_id',
		'nome',
		'ativo'
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

    public function perfis()
    {
    	return $this->belongsToMany(Perfil::class, 'acesso_perfil');
    }

    public function permissoes()
    {
    	return $this->belongsToMany(Permissao::class, 'acesso_permissao')->select('permissoes.id', 'permissoes.descricao');
    }


    public function getAcessosAtivos()
    {   
        return Acesso::select('id','ativo','nome')
            ->where('ativo', '=', true )
            ->queryOrderBy('nome')
            ->get();
    }

    public function getAcessosSelecinado($perfil_id)
    {   
        return Acesso::select('acessos.id','acessos.ativo','acessos.nome')
            ->join('acesso_perfil', 'acesso_perfil.acesso_id', '=', 'acessos.id')    
            ->where('ativo', '=', true )
            ->where('acesso_perfil.perfil_id', '=', $perfil_id )
            ->queryOrderBy('nome')
            ->get();
    }

    public function getAcessos($filtro)
    {   
        return Acesso::select('id','ativo','nome')
            ->selectRaw('(SELECT COUNT(*) FROM acesso_permissao ap WHERE ap.acesso_id = acessos.id ) AS permissoes')
            ->queryWhereNome($filtro->nome)
            ->queryWhereStatus($filtro->status)
            ->queryOrderBy($filtro->sort)
            ->paginate($filtro->por_pagina);
    }

    public function scopeQueryOrderBy( $query, $sort )
    {
        if ($sort != ''){
            return $query->orderByRaw($sort);
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

    public function getAcesso($id){
        return Acesso::select('acessos.id', 'acessos.nome', 'acessos.created_at', 'usuarios.nome as usuario_inclusao', 'acessos.updated_at', 'ua.nome as usuario_alteracao' )
                ->leftJoin('usuarios', 'usuarios.id','=','acessos.usuario_inclusao_id' )
                ->leftJoin('usuarios AS ua' , 'ua.id','=','acessos.usuario_alteracao_id' )
                ->where('acessos.id', '=', $id)
                ->first();
       
    }

    public function listarPermissoesRecursivo( ){
        $permissaoMenu = new PermissaoMenu;
        $data = $permissaoMenu->select('id', 'nome as text', 'menu_pai')
            ->selectRaw('\'fa fa-\'||icone AS icon, false as opened, false as selected, false as disabled, false as loading')
            ->where('id', '!=', 1)
            ->get();
        
        $data = $data->keyBy('id')->toArray();

        $dataRecursivo = $this->menuPermissaoRecursivo($data);

        return $dataRecursivo;
    }

    private function menuPermissaoRecursivo(array $menus, $children = 0)
    {
        $array_menu = array();
        $permissoes = new Permissao;

        foreach ($menus as $menu) 
        {
            if ($menu['menu_pai'] == $children) 
            {
                $filho = $this->menuPermissaoRecursivo($menus, $menu['id']);

                if ($filho) 
                {
                    $menu['children'] = $filho;
                    foreach ($filho as $key => $value)
                    {
                        $menu['children'][$key]['permissoes'] = $permissoes->select('id', 'descricao as text')->selectRaw('\'fa fa-key\' AS icon, false as opened, false as selected, false as disabled, false as loading')->where('menu_id', '=',  $value['id'])->orderBy('descricao', 'asc')->get();

                    }
                }
                
                $array_menu[] = $menu;
            }
        }

        return $array_menu;
    }

    public function getAcessoTree( $acessos )
    {
        $acessos = $acessos->map(function ($acesso, $key) {
            $acesso->text = $acesso->nome;
            $acesso->selected = false;
            $acesso->opened = false;
            $acesso->icon = 'fa fa-eye';
            return $acesso;
        });

        return $acessos;
    }
}
