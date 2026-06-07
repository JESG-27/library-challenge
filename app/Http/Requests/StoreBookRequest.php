<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'author' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'publication_date' => ['required', 'date'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'El título del libro no debe contener números.',
            'author.regex' => 'El nombre del autor no debe contener números.',
            'categories.min' => 'Debes asignar el libro a por lo menos una categoría.',
        ];
    }
}
