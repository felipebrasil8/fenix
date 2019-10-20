<?php

namespace App\Http\Requests\RH;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\RH\Departamento;

class DepartamentosRequest extends FormRequest
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
               
                $departamento = Departamento::find($this->departamento);
                
                if (empty($departamento))
                    return [];
               
                return [                   
                    'nome'         => 'required|max:100|'
                    .Rule::unique('departamentos')->ignore($departamento->id),
                    'descricao'    => 'required',
                    'gestor'       => 'required',
                    'area'       => 'required',
                    'ticket'    => 'required'
                ];
            }           
            case 'POST':
            {
                return [                   
                    'nome'         => 'required|unique:departamentos|max:100',
                    'descricao'    => 'required',
                    'gestor'       => 'required',
                    'area'       => 'required',
                    'ticket' => 'required'
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