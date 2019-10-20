<?php

namespace App\Http\Controllers\Core;

use Illuminate\Support\Str;

class Errors {

    public function msgDestroy($entidade){
        return 'Não foi possível alterar status de ' . $entidade;
    }

    public function msgStore($entidade){
        $str = new Str; 
        return 'Não foi possível cadastrar ' . $str->lower($entidade);
    }

    public function msgCopia($entidade){
        return 'Não foi possível copiar ' . $entidade;
    }

    public function msgUpdate($entidade){
        return 'Não foi possível editar ' . $entidade;
    }

    public function msgSearch(){
        return 'Não foi possível realizar a busca.';
    }

    public function mensagem($entidade, $e = ''){
        $mensagem[0] = $entidade . ' não encontrado(s)';
        if( \Config::get('app.debug') )
        {
            $mensagem[1] = $e;
        }
        return $mensagem;
    }

    public function descricaoVisualizar($entidade){
        $str = new Str;
        return "Visualizar " . $str->lower($entidade);
    }

    public function descricaoEditar($entidade){
        $str = new Str;
        return "Editar " . $str->lower($entidade);
    }

    public function descricaoCopiar($entidade){
        $str = new Str;
        return "Copiar " . $str->lower($entidade);
    }

    public function descricaoCadastrar($entidade){
        $str = new Str;
        return "Página Cadastrar " . $str->lower($entidade) . ' não localizada.';
    }

    public function msgAvatar(){
        return "Não foi possível editar Avatar";
        
    }

}
