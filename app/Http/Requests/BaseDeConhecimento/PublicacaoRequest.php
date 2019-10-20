<?php

namespace App\Http\Requests\BaseDeConhecimento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\BaseDeConhecimento\Publicacao;

class PublicacaoRequest extends FormRequest
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
        switch( $this->method() )
        { 
            case 'POST':
            {
                return [
                    'categoria_id'      => 'required|numeric',
                    'titulo'            => 'required|max:100',
                    'titulo' => Rule::unique('publicacoes')->where(function ($query) {
                        return $query->where('publicacao_categoria_id', '=', $this->categoria_id)
                                        ->where('titulo', '=', $this->titulo);
                    }),
                    'resumo'            => 'required',
                    'lista_relacionados'=> 'required|numeric|max:99|min:0',
                ];
            }
            case 'PUT':
            {
                return [
                    'id'                => 'required|numeric',
                    'categoria_id'      => 'required|numeric',
                    'titulo'            => 'required|max:100',
                    'titulo' => Rule::unique('publicacoes')->ignore($this->id)->where(function ($query) {
                        return $query->where('publicacao_categoria_id', '=', $this->categoria_id)
                                        ->where('titulo', '=', $this->titulo);
                    }),
                    'resumo'            => 'required',
                    'lista_relacionados'=> 'required|numeric|max:99|min:0',
                ];
            }            
                        
            

            default:break;
        }     
    }
}
