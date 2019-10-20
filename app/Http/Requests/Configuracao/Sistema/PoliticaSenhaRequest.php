<?php

namespace App\Http\Requests\Configuracao\Sistema;

use Illuminate\Validation\Rule;
use App\Models\Configuracao\Sistema\PoliticaSenha;
use Illuminate\Foundation\Http\FormRequest;

class PoliticaSenhaRequest extends FormRequest
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
        return [ 
            'tamanho_minimo' => 'required|numeric',
            'qtde_minima_letras' => 'required|numeric',
            'qtde_minima_numeros' => 'required|numeric',
            'qtde_minima_especial' => 'required|numeric',
            'caractere_especial' => 'required|max:20',
            'maiusculo_minusculo' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'tamanho_minimo.required' => 'Tamanho mínimo é obrigatorio.',
            'tamanho_minimo.numeric' => 'Tamanho mínimo deve ser um numéro.',
            'qtde_minima_letras.required' => 'Quantidade mínima de letras é obrigatoria.',
            'qtde_minima_letras.numeric' => 'Quantidade mínima de letras deve ser um numéro.',
            'qtde_minima_numeros.required' => 'Quantidade mínima de números é obrigatoria.',
            'qtde_minima_numeros.numeric' => 'Quantidade mínima de números deve ser um numéro.',
            'qtde_minima_especial.required' => 'Quantidade mínima de caracteres especiais é obrigatoria.',
            'qtde_minima_especial.numeric' => 'Quantidade mínima de caracteres especiais deve ser um numéro.',
            'caractere_especial.required' => 'Caracteres considerados especiais é obrigatorio.',
            'caractere_especial.max' => 'Caracteres considerados especiais deve conter no máximo 20 caracteres.',
            'maiusculo_minusculo.required' => 'Obrigar maiúsculo e minúsculo é obrigatorio.',
         
        ];
    }


}
