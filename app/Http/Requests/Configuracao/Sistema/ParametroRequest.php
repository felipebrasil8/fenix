<?php

namespace App\Http\Requests\Configuracao\Sistema;

use Illuminate\Validation\Rule;
use App\Models\Configuracao\Sistema\Parametro;
use App\Models\Configuracao\Sistema\ParametroGrupo;
use App\Models\Configuracao\Sistema\ParametroTipo;
use Illuminate\Foundation\Http\FormRequest;

class ParametroRequest extends FormRequest
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

                $parametro = Parametro::find($this->id);                               
                
                return [ 
                    'nome'  => 'required|max:50|'
                    .Rule::unique('parametros')->ignore($parametro->id),
                    'descricao' => 'required|max:200',
                    'grupo' => 'required',
                    'tipo' => 'required',
                    'ordem' => 'required|numeric',
                    'valor' => ( $parametro->obrigatorio ? 'required' : '' ),
                    'editar' => 'required'
                ];
                
            }            
            case 'POST': {

                return [
                    
                    'nome' => 'required|unique:parametros|max:50',
                    'descricao' => 'required|max:200',
                    'grupo' => 'required',
                    'tipo' => 'required',
                    'ordem' => 'required|numeric',
                    'valor' => 'required',
                    'editar' => 'required'
                ];              
            }

            default:break;

        }
    }
}
