<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

use App\Models\Core\MenuFilhoPai;
use App\Http\Controllers\Core\Errors;
use App\Http\Controllers\Core\Success;

use App\Util\Date;
use App\Util\FormatString;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $identificadorMigalha;

    public function autorizacao( $identificador )
    {
        if( empty(\Auth::user()) )
        {
            abort(401, 'Usuário não logado.');
        }
        else if( $identificador == '' )
        {
            abort(403, ['errors' => ['Falha na autenticação.']]);   
        }
        else
        {
            /** 
             * Seta a variável $identificadorMigalha para executar o método migalhaDePao
             */
            $this->identificadorMigalha = $identificador;

            /** 
             * Seta a variável $identificador no objeto user() para executar o método can()
             */
            \Auth::user()->identificador = strtoupper($identificador);

            if( \Auth::user()->can( $identificador ) == false )
            {
                abort(403, 'Falha na autenticação.');
            }
        }
    }

    public function migalhaDePao( $paginaAux = '' )
    {

       $meuPaiFilho = new MenuFilhoPai;
       return $meuPaiFilho->migalhaDePao( $this->identificadorMigalha, $paginaAux );
    }

    public function setIdentificadorMigalha( $menuId )
    {
        $this->identificadorMigalha = $menuId;
    }

    public function getIdentificadorMigalha()
    {
        return $this->identificadorMigalha;
    }

    public function strToUpperCustom($str)
    {
        return strtr(strtoupper($str),'àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ','ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß');
    }

    public function strToLowerCustom($str)
    {
        return strtr(strtolower($str),'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß','àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ');
    }

    public function ucFirstCustom($str)
    {
        return $this->strToUpperCustom( mb_substr($str, 0, 1, 'UTF-8') ).$this->strToLowerCustom( mb_substr($str, 1) );
    }

    public function paginaNaoEncontrada($param)
    {   
        return view( 'erros.naoEncontrado', $param );
    }

    public function date()
    {
        return new Date;
    }

    public function formatString()
    {
        return new FormatString;
    }

    public function errors()
    {
        return new Errors;
    }

    public function success()
    {
        return new Success;
    }


}
