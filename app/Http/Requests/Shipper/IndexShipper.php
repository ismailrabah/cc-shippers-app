<?php

namespace App\Http\Requests\Shipper;

use Illuminate\Foundation\Http\FormRequest;

class IndexShipper extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Return authorization on this opject using Gate Facade
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
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'per_page' => 'integer|nullable',
        ];
    }
}
