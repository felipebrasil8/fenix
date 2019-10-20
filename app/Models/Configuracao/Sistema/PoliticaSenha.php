<?php

namespace App\Models\Configuracao\Sistema;

use Illuminate\Database\Eloquent\Model;

class PoliticaSenha extends Model
{
    protected $fillable = array( 
		'ativo',
    	'tamanho_minimo', 
    	'qtde_minima_letras', 
    	'qtde_minima_numeros', 
    	'qtde_minima_especial', 
    	'caractere_especial', 
    	'maiusculo_minusculo',
        'usuario_exclusao_id',
        'dt_exclusao'
	);

    /**
     * Funcao que verifica se a senha informada esta de acordo com a regra de complexidade
     */
    public function complexidadeSenha( $senha )
    {
        $result = array();

        if( ! (strlen( $this->existeLetras($senha) ) >= $this->qtde_minima_letras) )
        {
            array_push($result, $this->getInfoQtdeMinimaLetras());
        }

        if( ! (strlen( $this->existeNumero($senha) ) >= $this->qtde_minima_numeros) )
        {
            array_push($result, $this->getInfoQtdeMinimaNumeros());
        }

        if( ! (strlen( $this->existeCharEspecial($senha, $this->caractere_especial) ) >= $this->qtde_minima_especial) )
        {
            array_push($result, $this->getInfoQtdeMinimaEspecial());
        }

        if( $this->maiusculo_minusculo == 't' )
        {
            if( ! ( strlen( $this->existeMaiusculo($senha) ) > 0 &&
                strlen( $this->existeMinusculo($senha) ) > 0 ) )
            {
                array_push($result, $this->getInfoMaiuscula());
                array_push($result, $this->getInfoMinuscula());
            }
        }

        if( ! (strlen( $senha ) >= $this->tamanho_minimo) )
        {
            array_push($result, $this->getInfoTamanhoMinimo());
        }

        return $result;
    }

    private function existeLetras($str)
    {
        return preg_replace("/[^a-zA-Z]/", "", $str);
    }

    private function existeNumero($str)
    {
        return preg_replace("/[^0-9]/", "", $str);
    }

    private function existeMaiusculo($str)
    {
        return preg_replace("/[^A-Z]/", "", $str);
    }

    private function existeMinusculo($str)
    {
        return preg_replace("/[^a-z]/", "", $str);
    }

    private function existeCharEspecial($str, $param)
    {
        return preg_replace("/[^".$param."]/", "", $str);
    }

    public function getInformacoes()
    {
        $result = array();

        if( $this->qtde_minima_letras > 0 )
        {
            array_push($result, $this->getInfoQtdeMinimaLetras());
        }

        if( $this->qtde_minima_numeros > 0 )
        {
            array_push($result, $this->getInfoQtdeMinimaNumeros());
        }

        if( $this->qtde_minima_especial > 0 )
        {
            array_push($result, $this->getInfoQtdeMinimaEspecial());
        }

        if( $this->maiusculo_minusculo == 't' )
        {
            array_push($result, $this->getInfoMaiuscula());
            array_push($result, $this->getInfoMinuscula());
        }

        if( $this->tamanho_minimo > 0 )
        {
            array_push($result, $this->getInfoTamanhoMinimo());
        }

        return $result;
    }

    /** 
     *  Get's de mensagens de política de senha
     */
    public function getInfoQtdeMinimaLetras()
    {
        return 'Pelo menos '.$this->qtde_minima_letras.($this->qtde_minima_letras > 1 ? ' letras' : ' letra');
    }

    public function getInfoQtdeMinimaNumeros()
    {
        return 'Pelo menos '.$this->qtde_minima_numeros.($this->qtde_minima_numeros > 1 ? ' números' : ' número');
    }

    public function getInfoQtdeMinimaEspecial()
    {
        return 'Pelo menos '.$this->qtde_minima_especial.($this->qtde_minima_especial > 1 ? ' caracteres especiais' : ' caractere especial');
    }

    public function getInfoMaiuscula()
    {
        return 'Pelo menos 1 letra maíuscula';
    }

    public function getInfoMinuscula()
    {
        return 'Pelo menos 1 letra minúscula';
    }

    public function getInfoTamanhoMinimo()
    {
        return 'Tamanho mínimo: '.$this->tamanho_minimo.($this->tamanho_minimo > 1 ? ' caracteres' : ($this->tamanho_minimo == 1 ? ' caractere' : ''));
    }
}
