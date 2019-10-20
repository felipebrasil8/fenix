<?php

namespace App\Http\Requests\RH;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\RH\Funcionario;

class FuncionarioRequest extends FormRequest
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

                $funcionario = Funcionario::find($this->id);
                
                if (empty($funcionario))
                    return [];                           
                
                $validation = array( 
                    'nome' => array('required', 'max:100', Rule::unique('funcionarios')->ignore($funcionario->id)),
                    'email' => array('required', 'max:50', 'email', Rule::unique('funcionarios')->ignore($funcionario->id)),
                    'dt_nascimento' => array('required', 'date_format:"d/m/Y"', 'before_or_equal:31/12/'.date('Y').'', 'after_or_equal:01/01/1900'),
                    'celular_pessoal' => array('required', 'regex:/^[1-9]{2}[9]{1}[2-9]{1}[0-9]{7}$/'),
                    'telefone_comercial' => array('required', 'regex:/^[1-9]{2}[2-9]{1}[0-9]{7}$/'),
                    'ramal' => array('required', 'regex:/^[1-9]{1}[0-9]{3}$/')
                    
                );

                if( !is_null($this->celular_corporativo) )
                {
                    $array = array('celular_corporativo' => array('regex:/^[1-9]{2}[9]{1}[2-9]{1}[0-9]{7}$/'));
                    $validation = array_merge($validation, $array);
                }

                return $validation;
            }            
            case 'POST': {
                
                $validation = array( 
                    'nome' => array('required', 'unique:funcionarios', 'max:100'),
                    'email' => array('required', 'unique:funcionarios', 'max:50', 'email'),
                    'dt_nascimento' => array('required', 'date_format:"d/m/Y"', 'before_or_equal:31/12/'.date('Y').'', 'after_or_equal:01/01/1900'),
                    'celular_pessoal' => array('required', 'regex:/^[1-9]{2}[9]{1}[2-9]{1}[0-9]{7}$/'),
                    'telefone_comercial' => array('required', 'regex:/^[1-9]{2}[2-9]{1}[0-9]{7}$/'),
                    'ramal' => array('required', 'regex:/^[1-9]{1}[0-9]{3}$/')
                    
                );

                if( !is_null($this->celular_corporativo) )
                {
                    $array = array('celular_corporativo' => array('regex:/^[1-9]{2}[9]{1}[2-9]{1}[0-9]{7}$/'));
                    $validation = array_merge($validation, $array);
                }

                return $validation;
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
        