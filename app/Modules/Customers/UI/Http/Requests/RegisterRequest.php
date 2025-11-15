<?php

namespace App\Modules\Customers\UI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:100', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'regex:/^[0-9]{10,15}$/', Rule::unique('users', 'phone')->whereNotNull('phone')],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'terms' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre completo',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'password' => 'contraseña',
            'terms' => 'términos y condiciones',
        ];
    }
}
