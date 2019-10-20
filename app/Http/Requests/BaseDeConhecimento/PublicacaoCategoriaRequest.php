<?php

namespace App\Http\Requests\BaseDeConhecimento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PublicacaoCategoriaRequest extends FormRequest
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
                    'nome'            => 'required|max:100',
                    'nome' => Rule::unique('publicacoes_categorias')->where(function ($query) {
                        return $query->where('publicacao_categoria_id', '=', $this->categoria_id)
                                        ->where('nome', '=', $this->nome);
                    }),
                    'descricao'            => 'required',
                    'icone'            => 'required',
                    'ordem'=> 'required|numeric|max:9999|min:0',
                ];
            }
            case 'PUT':
            {
                return [
                    'id'                => 'required|numeric',
                    'nome'            => 'required|max:100',
                    'nome' => Rule::unique('publicacoes_categorias')->ignore($this->id)->where(function ($query) {
                        return $query->where('publicacao_categoria_id', '=', $this->categoria_id)
                                        ->where('nome', '=', $this->nome);
                    }),
                    'descricao'        => 'required',
                    'icone'            => 'required',
                    'ordem'=> 'required|numeric|max:9999|min:0',
                ];
            }            
                        
            

            default:break;
        }     
    }
}
