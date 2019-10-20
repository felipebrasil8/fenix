<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\RH\Cargos;
use Illuminate\Validation\Rule;

class CargoRequest extends FormRequest
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

                $cargo = Cargos::find($this->cargo);                                               

                if (empty($cargo))
                    return [];

                return [ 
                    'nome' => 'required|max:255|'
                    .Rule::unique('cargos')->ignore($cargo->id),
                    'descricao'     => 'required',
                    'gestor'        => 'required',
                    'departamento'  => 'required',
                ];

            }  

            case 'POST': 
            {
                return [
                    'nome'          => 'required|unique:cargos|max:100',
                    'descricao'     => 'required',
                    'gestor'        => 'required',
                    'departamento'  => 'required',
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
