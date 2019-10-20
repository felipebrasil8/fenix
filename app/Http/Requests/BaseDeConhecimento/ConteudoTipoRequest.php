<?php

namespace App\Http\Requests\BaseDeConhecimento;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validação do método setConteudoTipo em ConteudoController 
 * para adicionar um novo tipo de conteúdo na publicação
 */
class ConteudoTipoRequest extends FormRequest
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
           'publicacao_id'    => 'required|numeric',
           'conteudo_tipo_id' => 'required|numeric',
        ];
    }
}
