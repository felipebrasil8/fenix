<?php

namespace App\Http\Requests\Configuracao\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Configuracao\Ticket\Prioridade;
use Illuminate\Validation\Rule;

class CampoAdicionalPrioridadeRequest extends FormRequest
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
                    'nome'             => 'required|unique:tickets_prioridade|max:255',
                    'departamento_id'  => 'required|max:255',
                    'cor'              => 'required|max:7',
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