<?php

namespace App\Util;

class FormatString
{
    public function ajustaLink( $descricao ){
        
        $descricao = str_replace("\t"," \t ", str_replace("\n"," \n ",$descricao));
        $descricao = explode(" ", $descricao);

        $descricao = array_map(function($value) {
             
            if ( strpos($value, 'HTTP://') === 0 || strpos($value, 'HTTPS://') === 0 ){
                $link = strtolower($value);
                return '<a href="'.$link.'" target="_blank">'.$link.'</a>';                           
            }
            
            $padraoEmail = '/[áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇa-zA-z0-9.-]+\@[áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇa-zA-z0-9.-]+\.[áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇa-zA-Z]+./';
            if ( preg_match($padraoEmail, $value, $matches) ){

                $link = mb_strtolower(strtolower($matches[0]));
                return '<a href="mailto:'.$link.'">'.$link.'</a>';                           

            }

            return $value;

        },$descricao);

        $descricao = implode(" ", $descricao);
      
        return nl2br($descricao);
    }

    public function strToUpperCustom($str)
    {
        return strtr(strtoupper($str),'àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ','ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß');
    }

    public function strToUpperSemAcento($str)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","A A E E I I O O U U N N Ç Ç"), $this->strToUpperCustom($str));
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
}