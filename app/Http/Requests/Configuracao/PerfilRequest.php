<?php

namespace App\Http\Requests\Configuracao;

use Illuminate\Validation\Rule;
use App\Models\Configuracao\Perfil;
use Illuminate\Foundation\Http\FormRequest;

class PerfilRequest extends FormRequest
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

                $perfil = Perfil::find($this->id);                               
                
                return [ 
                    'nome'  => 'required|max:100|'

                    .Rule::unique('perfis')->ignore($perfil->id)
                    
                ];
                
            }            
            case 'POST': {

                return [
                    
                    'nome' => 'required|unique:perfis|max:100'
                    
                ];              
            }
            case 'DELETE': {

                return [
                    
                    'id' => 'required|numeric'
                    
                ];              
            }
            default:break;
        }
    }
}
