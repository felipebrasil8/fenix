<?php

namespace App\Http\Requests\Configuracao;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Configuracao\Usuario;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
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
                $usuario = Usuario::find($this->id);                               
                
                return [ 
       

                    'usuario' => 'required|max:255|' 
                    .Rule::unique('usuarios')->ignore($usuario->id),

                    'nome' => 'required|max:255|'
                    .Rule::unique('usuarios')->ignore($usuario->id)
                    
                ];
                
            }            
            case 'POST': {

                return [                    
                    'nome'                => 'required|unique:usuarios|max:255',
                    'perfil'              => 'required',
                    'usuario'             => 'required|unique:usuarios|max:255',
                    'senha'               => 'required',
                    'campoConfirmarSenha' => 'required|same:senha'
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