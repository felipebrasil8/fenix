<?php

namespace App\Http\Requests\Monitoramento;

use Illuminate\Foundation\Http\FormRequest;

class MonitoramentoServidoresRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch( $this->method() ) {

            case 'PUT': {                
                
                return [ 
                    'porta_api'  => 'required|numeric|',
                    'executa_porta'  => 'required',                    
                    'executa_ping'  => 'required'                    
                ];
                
            }
            default:break;
        }
    }
}
