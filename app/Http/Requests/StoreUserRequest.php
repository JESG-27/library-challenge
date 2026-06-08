<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => ['required', 'string', 'regex:/^[\pL\s\-]+$/u'],
            'email' => 'required|email|unique:users,email,' . ($userId ?? 'NULL'),
            'password' => $userId ? 'nullable|string|min:6' : 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'El nombre del usuario no debe contener números ni caracteres especiales.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
        ];
    }
}
