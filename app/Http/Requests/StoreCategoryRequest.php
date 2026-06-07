<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'description' => ['required', 'string', 'min:10'],
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'El nombre de la categoría no debe contener números.',
        ];
    }
}
