<?php

namespace App\Http\Requests\Configuracao\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Configuracao\Ticket\Status;
use Illuminate\Validation\Rule;

class CampoAdicionalStatusRequest extends FormRequest
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
            
            case 'POST': {

                return [
                    'nome'       => 'required|max:255',
                    'descricao'  => 'required',
                    'cor'        => 'required|max:7',
                    'ordem'  => 'required|numeric',
                ];              
            }            
            case 'DELETE': {

                return [                    
                    'id' => 'required|numeric'                    
                ];              
            }
            default:break;

        }
        
    }
}