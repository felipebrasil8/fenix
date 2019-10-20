<?php

namespace App\Http\Requests\Configuracao\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Configuracao\Ticket\Categoria;

class CategoriaRequest extends FormRequest
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
               
                $categoria = Categoria::find($this->categoria);
                
                if (empty($categoria))
                    return [];
               
                return [                   
                    'nome'         => 'required|max:100|'
                    .Rule::unique('tickets_categoria')->ignore($categoria->id),
                    'descricao'    => 'required',
                    'departamento_id' => 'required',
                    
                ];
            }           
            case 'POST':
            {
                return [                   
                    'nome'         => 'required|unique:tickets_categoria|max:100',
                    'descricao'    => 'required',
                    'departamento_id' => 'required',
                   
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