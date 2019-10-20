<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Core\Permissao;
use App\Models\Core\Menu;

class MenuFilhoPai extends Model
{
    public function migalhaDePao( $identificador='', $paginaAux='' )
    {
        if( $identificador == 'HOME' )
        {
            $idMenu = Menu::where('nome','HOME')
                ->select('id')
                ->get();
                
            if( !empty($idMenu->get(0)) )
            {
                $idMenu = $idMenu->get(0)->id;
            }
            else
            {
                $idMenu = '';   
            }
        }
        else
        {
            $idMenu = Permissao::where('identificador', '=', $identificador)
                ->select('menu_id')
                ->get();

            if( !empty($idMenu->get(0)) )
            {
                $idMenu = $idMenu->get(0)->menu_id;
            }
            else
            {
                $idMenu = '';   
            }
        }

        if( $idMenu != '' )
        {
            $migalhas = DB::select('SELECT * FROM menu_filho_pai( ? ) AS tabela_auxiliar(id integer, menu_id integer, nome text, icone text, url text, prioridade integer) ORDER BY prioridade DESC;', [$idMenu]);

            if( !empty($migalhas) )
            {
                return $this->adicionaPagina( $migalhas, $identificador, $paginaAux );
            }
            else
            {
                return array();
            }
        }
        else
        {
            return array();
        }
    }

    public function adicionaPagina( $migalhas, $identificador, $paginaAux )
    {
        $arrayMigalhas = array();
        
        /**
         * Converte os obejtos em array
         */
        foreach ($migalhas as $migalha)
        {
            array_push($arrayMigalhas, ['nome' => $migalha->nome, 'url' => $migalha->url, 'icone' => $migalha->icone, 'class' => '']);
        }

        if( $paginaAux != '' )
        {
            if( $paginaAux == 'INDEX' )
            {
                array_push($arrayMigalhas, ['nome' => 'PESQUISAR', 'url' => '', 'icone' => 'search', 'class' => 'active']);
            }
            elseif( $paginaAux == 'CONFIGURAR' )
            {
                array_push($arrayMigalhas, ['nome' => 'CONFIGURAR', 'url' => '', 'icone' => 'cogs', 'class' => 'active']);   
            }
            elseif( $paginaAux == 'VISUALIZAR_TICKET' )
            {
                array_push($arrayMigalhas, ['nome' => 'VISUALIZAR', 'url' => '', 'icone' => 'file-text-o', 'class' => 'active']);   
            }
            elseif( $paginaAux == 'CADASTRAR' )
            {
                array_push($arrayMigalhas, $this->arrayCadastrar( 'CADASTRAR' ) );   
            }
        }
        else
        {
            $arrayIdentificador = explode('_', $identificador);
            $pagina = array_pop($arrayIdentificador);

            if( $pagina == 'VISUALIZAR' )
            {
                array_push($arrayMigalhas, ['nome' => $pagina, 'url' => '', 'icone' => 'file-text-o', 'class' => 'active']);
            }
            elseif( $pagina == 'CADASTRAR' )
            {
                array_push($arrayMigalhas, $this->arrayCadastrar( $pagina ) );
            }
            elseif( $pagina == 'EDITAR' )
            {
                array_push($arrayMigalhas, ['nome' => $pagina, 'url' => '', 'icone' => 'pencil-square-o', 'class' => 'active']);   
            }
            elseif( $pagina == 'COPIAR' )
            {
                array_push($arrayMigalhas, ['nome' => $pagina, 'url' => '', 'icone' => 'files-o', 'class' => 'active']);   
            }
        }

        return $arrayMigalhas;
    }

    private function arrayCadastrar( $pagina )
    {
        return ['nome' => $pagina, 'url' => '', 'icone' => 'plus-square', 'class' => 'active'];
    }

}
