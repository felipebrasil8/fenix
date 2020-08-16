<?php

namespace App\Util;

class ValidateIpAddress
{
    private $mensagem = '';

    public function __construct()
    {
        $this->mensagem = '';
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function setMensagem( $str )
    {
        $this->mensagem = $str;
        return $this->mensagem;
    }

    public function validaRedes( $strRedes )
    {
        try{
            $redes = explode ( ',', $strRedes );
            if      ( count ( $redes ) > 10 ) return $this->setMensagem('Quantidade de redes maior que a permitida.');
            else if ( count ( $redes ) > 0 )
            {
                foreach ( $redes as $rede ) {
                    list ( $ip, $mascara ) = explode ( '/', $rede );
                    if ( !( $this->validaIP ( $ip ) && $this->retornaMascaraIP ( trim ( $mascara ) ) ) ) return $this->setMensagem('Máscara da rede inválida.');
                }
            }
        }catch( \Exception $e ){
            return $this->setMensagem('Erro ao validar IP/Máscara.');
        }
    }

    public function validaIP ( $ip )
    {
        $ipv4 = explode ( '.', $ip );
        if ( count ( $ipv4 ) != 4 || !preg_match("/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/", $ip ) ) return false;
        for ( $i = 0; $i < count ( $ipv4 ); $i++ ) if ( $ipv4[$i] > 255 ) return false;
        return true;
    }

    public function retornaMascaraIP ( $mascara )
    {
        if ( strlen ( $mascara ) > 2 ) { if ( !$this->validaIP ( $mascara ) ) $mascara = false; }
        else if ( $mascara > 7 && $mascara < 33 ) {
            $byte = ( int ) $mascara / 8;
            $bit  = ( int ) $mascara % 8;
            $mascara = '';
            for ( $i=1; $i<=4; $i++ ) {
                if ( $i <= $byte ) $mascara .= ( $mascara != '' ? '.' : '' ).'255';
                else if ( $bit > 0 ) {
                    $mascara .= ( $mascara != '' ? '.' : '' ).( 254 - ( pow ( 2, ( 8-$bit ) )-2 ) );
                    $bit = 0;
                }
                else $mascara .= ( $mascara != '' ? '.' : '' ).'0';
            }
        }
        else $mascara = false;

        return $mascara;
    }

    public function VericaRede($rede){
	    return true;
	    $rede = explode(',', $rede );
        
        foreach( $rede as $k=>$v ){
            $rede[$k] = explode('/', $v);
        }

        foreach( $rede as $key=>$value ){
            if ( ( $rede[$key][0] != '' && $rede[$key][1] != '' ) ) {
                if ( $this->ipPertence( $_SERVER['REMOTE_ADDR'],$rede[$key][0],$rede[$key][1] ) ) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Função para verificar o acesso do cliente por ip
     **/
    public function ipPertence ( $ip,$rede,$mascara ) {
        if ( !$this->validaIP( $rede ) ) return false;
        if ( $mascara =  $this->retornaMascaraIP ( $mascara ) ) {
            // Dividimos en octetos
            $octip   = explode ( '.',$ip      );
            $octnet  = explode ( '.',$rede    );
            $octmask = explode ( '.',$mascara );
            // Comparamos con AND binario
            for ( $i=0; $i<4; $i++ ) {
                $a = ( int ) $octip [$i] & ( int ) $octmask[$i];
                $b = ( int ) $octnet[$i] & ( int ) $octmask[$i];
                if ( $a != $b ) return false;
            }
            return true;
        }
        return false;
    }

}
