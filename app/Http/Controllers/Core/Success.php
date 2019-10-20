<?php

namespace App\Http\Controllers\Core;

use Illuminate\Support\Str;

class Success {

    public function msgStore( $entidade ){
        return $entidade . ' cadastrado com sucesso!';
    }

    public function msgUpdate($entidade){
        return $entidade . ' atualizado com sucesso!';
    }

    public function msgCopy($entidade){
        return $entidade . ' copiado com sucesso!';
    }

    public function msgDestroy( $entidade ){
        return $entidade . ' excluído com sucesso!';
    }

    public function msgStoreImagem(  ){
        return 'Imagem cadastrada com sucesso!';
    }

}
