<?php

namespace App\Models\Core;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = array(
		'nome',
		'descricao',
		'icone',
		'url',
		'ordem',
		'ativo'		
	);

	public function menuRecursivo(array $menus, $parente = 0)
    {
        $array_menu = array();
        $active = false;

        foreach ($menus as $menu) 
        {

            // Verifica se é a URL atual
            if ( $menu['url'] != '' && $menu['url'] != '/' && starts_with(url()->current(),asset($menu['url'])) && !$active ){
                $menu['active'] = 'active';
                $active = true;
            }
            else if ( empty(Request::segment(1)) && $menu['url'] == '/' && !$active ){
                $menu['active'] = 'active';
                $active = true;
            }
            else
                $menu['active'] = '';

            if ($menu['menu_pai'] == $parente) 
            {
                $filho = $this->menuRecursivo($menus, $menu['id']);
                if ($filho) 
                {
                    $menu['parente'] = $filho;

                    // Verifica se alguns dos filhos possui está com active
                    if ( !empty(array_where($menu['parente'], function ($value, $key) { return $value['active'] == 'active'; } ) ) )
                        $menu['active'] = 'active';

                }
                $array_menu[] = $menu;
            }

        }

        return $array_menu;
    }

    public function listarMenu()
    {

        $menus = PermissaoMenu::select('id', 'nome', 'icone', 'url', 'menu_pai')
                ->where('usuario_id', '=', auth()->id())
                ->orWhere('nome', '=', 'HOME')
                ->get();
            
        $menus = $menus->keyBy('id')->toArray();
        $menu_recursivo = $this->menuRecursivo($menus);
        
        return $menu_recursivo;
    }

  
}



