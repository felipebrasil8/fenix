<?php

namespace App\Http\Requests\RH;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\RH\Area;

class AreaRequest extends FormRequest
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

            case 'PUT':
            {
                
                $area = Area::find($this->area); 
                
                if (empty($area))
                    return [];

                return [                    
                    'nome'         => 'required|max:100|'
                    .Rule::unique('areas')->ignore($area->id),
                    'descricao'    => 'required',
                    'gestor'       => 'required'
                ];

            }            
            case 'POST':
            {
                return [                    
                    'nome'         => 'required|unique:areas|max:100',
                    'descricao'    => 'required',
                    'gestor'       => 'required'
                ];
            }            
            case 'DELETE':
            {
                return [                    
                    'id' => 'required|numeric'                    
                ];              
            }

            default:break;
        }     
    }
}
        