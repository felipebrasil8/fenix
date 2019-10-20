<?php

namespace App\Util;

use Carbon\Carbon;
use App\Http\Controllers\Core\ExcelController;
class Date
{
    /**
     * Formata a data para ser inserida no banco de dados
     * return Carbon::createFromFormat('d/m/Y', $strDate, 'America/Sao_Paulo')->format('Y-m-d H:i:s')
     */
    public function setFormatDate( $strDate='', $formatIn='d/m/Y', $formatOut='Y-m-d H:i:s', $tz='America/Sao_Paulo' )
    {
        return Carbon::createFromFormat($formatIn, $strDate, $tz)->format($formatOut);
    }

    /**
     * Formata a data para ser exibida na tela
     */
    public function getFormatDate( $dateTime='', $format='d/m/Y' )
    {
        return Carbon::parse($dateTime)->format($format);
    }

    /**
     * Este metodo faz a conta pra mostrar a dta referente ( ex:se for ate 59 minutos, se for mais de uma hora em horas )
     */
    public function dataInteracoes( $created_at )
    {
        $date1 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create( $created_at ), 'd/m/Y H:i:s'));
        $date2 = Carbon::createFromFormat('d/m/Y H:i:s', date_format(date_create(Carbon::now('America/Sao_Paulo')), 'd/m/Y H:i:s'));

        if( $date2->diffInDays($date1) == 1 )
        {
            $valor = '1 dia atrás'; 
        }
        else if( $date2->diffInDays($date1) > 1 )
        {
            $valor = $date2->diffInDays($date1).' dias atrás';
        }
        else if( $date2->diffInHours($date1) == 1 )
        {
            $valor = '1 hora atrás';
        }
        else if( $date2->diffInHours($date1) > 1 )
        {
            $valor = $date2->diffInHours($date1).' horas atrás';
        }
        else if( $date2->diffInMinutes($date1) == 1 )
        {
            $valor = '1 minuto atrás';
        }
        else if( $date2->diffInMinutes($date1) > 1 )
        {
            $valor = $date2->diffInMinutes($date1).' minutos atrás';
        }
        else
        {
            $valor = $date2->diffInSeconds($date1).' segundos atrás';
        }

        return $valor;        
    }

    /**
     * Método que retorna se o segundo parametro (data) é maior ou igual que o primeiro
     */
    public function dateDiff( $date1, $date2, $format = 'd/m/Y')
    {
        $ndate1 = Carbon::createFromFormat($format, $date1);
        $ndate2 = Carbon::createFromFormat($format, $date2);
        
        if( $ndate2->diffInDays($ndate1) >= 1 )
        {
            return true;            
        }

        return false;
    }

    public function menorAgora( $data, $format = 'd/m/Y' )
    {
        return Carbon::createFromFormat($format, $data)->lt(Carbon::now());
    }

    public function menorOuIgualAgora( $data, $format = 'd/m/Y' )
    {
        return Carbon::createFromFormat($format, $data)->lte(Carbon::now());
    }

    public function maiorAgora( $data, $format = 'd/m/Y' )
    {
        return Carbon::createFromFormat($format, $data)->gt(Carbon::now());
    }

    public function maiorOuIgualAgora( $data, $format = 'd/m/Y' )
    {
        return Carbon::createFromFormat($format, $data)->gte(Carbon::now());
    }

    public function formataDataExcel($data){
        return (int)$this->valorDataExcel($data);
    }

    public function formataHoraExcel($data){
        return $this->valorDataExcel($data)-(int)$this->valorDataExcel($data);
    }

    private function valorDataExcel($data){
        $excel = new ExcelController;
        return $excel->PHPToExcel(strtotime($data));
    }

    /**
     * [dataIntervaloSegundoString Converte um valor em segundos para a string da quantidadde de Anos, meses, dias, minutos e segundos.]
     * @param  [int] $segundos [Quantidade de segundos]
     * @return [type]           [String]
     */
    public function dataIntervaloSegundoString($segundos) {
        $retorno ='';

            $minuto = 60;
            $hora = $minuto * 60;
            $dia = $hora * 24;

            $temp_d = intval($segundos/$dia);
            $temp_r = $segundos%$dia;

            if ($temp_d > 0)
            {
                $retorno = $temp_d.($temp_d > 1?' dias ':' dia '); 
                return $retorno;
            }

            $temp_h = intval($temp_r/$hora);
            $temp_r = $temp_r%$hora;

            if ($temp_h > 0)
            {
                $retorno = $retorno.str_pad($temp_h, 2, '0', STR_PAD_LEFT ).':'; 
            }
            else
            {
                $retorno = $retorno.'00:'; 
            }

            $temp_m = intval($temp_r/$minuto);
            $temp_r = $temp_r%$minuto;
            
            if ($temp_m > 0)
            {
                $retorno = $retorno.str_pad($temp_m, 2, '0', STR_PAD_LEFT ).':'; 
            }
            else
            {
                $retorno = $retorno.'00:'; 
            }

            $retorno = $retorno.str_pad($temp_r, 2, '0', STR_PAD_LEFT ); 
        return $retorno;
    }

    /**
     * Formata a data e hora para ser exibida na tela exemplo: 08/10/2018 11:07:53
     */
    public function getFormatDateHour( $dateTime='', $format='d/m/Y h:m:s' )
    {
        return Carbon::parse($dateTime)->format($format);
    }


}