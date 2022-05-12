<?php

namespace App\Http\Requests\Shipper;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShipper extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Return authorization on this opject
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
            'company_name' => ['required', 'string', 'min:3', 'max:255'],
            'address' => ['required', 'string', 'min:3', 'max:500'],
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
