<?php

namespace App\Http\Requests\Configuracao;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Configuracao\Acesso;

class AcessoRequest extends FormRequest
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

                $acesso = Acesso::find($this->acesso); 
                
                return [ 
                    'nome'  => 'required|max:100|'
                    .Rule::unique('acessos')->ignore($acesso->id)
                ];
                
            }            
            case 'POST': {

                return [
                    'nome' => 'required|unique:acessos|max:100'
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
