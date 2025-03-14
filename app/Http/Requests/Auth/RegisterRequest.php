<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required', 'string', 'unique:users,name'],
            "email" => ['required', 'email', 'unique:users,email'],
            "password" => ['required', 'confirmed', Password::defaults(),'min:8','max:16']
        ];
    }
    public function getRedirectUrl(): string
    {
        return '';
    }
}
