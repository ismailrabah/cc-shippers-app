<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class DestroyContact extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
    
    /**
    * Modify input data
    *
    * @return array
    */
    public function sanitizedArray(): array
    {
        $sanitized = $this->validated();
        //Add your code for manipulation with request data here
        return $sanitized;
    }

    /**
    * Return modified (sanitized data) as a php object
    * @return  object
    */
    public function sanitizedObject(): object {
        $sanitized = $this->sanitizedArray();
        return json_decode(collect($sanitized));
    }
}
