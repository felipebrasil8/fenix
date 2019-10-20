<?php

namespace App\Http\Requests\Configuracao;

use Illuminate\Foundation\Http\FormRequest;

class NovaSenhaRequest extends FormRequest
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
            'newPassword' => 'required',
            'newPasswordConfirmation' => 'required|same:newPassword',
        ];
    }

    public function messages()
    {
        return [
            'newPassword.required' => 'É preciso informar a nova senha.',
            'newPasswordConfirmation.required' => 'A confirmação da nova senha deve ser informada.',
            'newPasswordConfirmation.same' => 'A nova senha e a confirmação dela estão diferentes.',
        ];
    }
}
