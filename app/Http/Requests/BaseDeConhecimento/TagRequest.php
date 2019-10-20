<?php

namespace App\Http\Requests\BaseDeConhecimento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
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
	    		
                    'publicacao_id'      => 'required|numeric',
                 
	  			]; 
            }            

            default:break;
        }     
    }

    
   
}
