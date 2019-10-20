<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Ticket\Ticket;

class TicketRequest extends FormRequest
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
                return [                    
                    'status'           => 'required|numeric',
                    'solicitante'      => 'required|numeric',
                    'prioridade'       => 'required|numeric',
                    'assunto'          => 'required|max:100',
                    'categoria'        => 'required|numeric',
                    'subcategoria'     => 'required',
                    'responsavel'      => 'required|numeric',
                    'mensagem'         => 'required',
                    'interno'        => 'required', 
                ];
            }            
            case 'POST':
            {
                return [                    
                    'departamento'     => 'required',
                    'solicitante'      => 'required',
                    'prioridade'       => 'required',
                    'assunto'          => 'required|max:100',
                    'categoria'        => 'required',
                    'subcategoria'     => 'required',
                    'descricao'        => 'required',
                ];
            }            
            case 'DELETE':
            {
                
            }

            default:break;
        }     
    }
}
        