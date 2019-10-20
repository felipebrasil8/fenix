<?php

namespace App\Http\Controllers\Core;

use Excel;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ExcelController extends Controller {

    const CALENDAR_WINDOWS_1900 = 1900;     //  Base date of 1st Jan 1900 = 1.0
    const CALENDAR_MAC_1904 = 1904;         //  Base date of 2nd Jan 1904 = 1.0

    /**
     * @link: http://laravelcode.com/post/excel-and-csv-import-export-using-maatwebsite-in-laravel-example
     * @link http://www.maatwebsite.nl/laravel-excel/docs/reference-guide#sheet-properties
     */
	public function downloadExcel($type, $data, $name, $cabecalho, $formato = array()) {

        $nomeArquivo = $name.Carbon::now()->format('YmdHis');

        return Excel::create($nomeArquivo, function($excel) use ($data, $cabecalho, $formato) {

            $excel->sheet('mySheet', function($sheet) use ($data, $cabecalho, $formato) {               
                          
                $sheet->setColumnFormat($formato);
                
                $sheet->fromArray($data, null, 'A1', false, false);                
                $sheet->prependRow( $cabecalho );
                $sheet->row(1, function($row) {
                    $row->setFontColor('#3c8dbc');
                    $row->setFontWeight('bold'); 
                });
            });
                        
        })->download($type);
    }

    /**
     *  Convert a date from PHP to Excel
     *
     *  @param  mixed       $dateValue          PHP serialized date/time or date object
     *  @param  boolean     $adjustToTimezone   Flag indicating whether $dateValue should be treated as
     *                                                  a UST timestamp, or adjusted to UST
     *  @param  string      $timezone           The timezone for finding the adjustment from UST
     *  @return mixed       Excel date/time value
     *                          or boolean FALSE on failure
     */
    public function PHPToExcel($dateValue = 0, $adjustToTimezone = FALSE, $timezone = NULL) {
        $saveTimeZone = date_default_timezone_get();
        $retValue = FALSE;
        if ((is_object($dateValue)) && ($dateValue instanceof DateTime)) {
            $retValue = \PHPExcel_Shared_Date::FormattedPHPToExcel( $dateValue->format('Y'), $dateValue->format('m'), $dateValue->format('d'),
                                                   $dateValue->format('H'), $dateValue->format('i'), $dateValue->format('s')
                                                 );
        } elseif (is_numeric($dateValue)) {
            $retValue = \PHPExcel_Shared_Date::FormattedPHPToExcel( date('Y',$dateValue), date('m',$dateValue), date('d',$dateValue),
                                                   date('H',$dateValue), date('i',$dateValue), date('s',$dateValue)
                                                 );
        }
        date_default_timezone_set($saveTimeZone);

        return $retValue;
    }   //  function PHPToExcel()

}

